<?php

// Initialize session
session_start();

require __DIR__ . '/../vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf;

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== false) {
	header('location: /');
	exit;
}


/*----------------------Début: Récupération des paramètres saisis dans le fomulaire HTML-----------------------------------------*/
$tailleEmptyErr = "";

if (isset($_POST["submit"])) {
    //Récupération des paramètres saisis dans le fomulaire HTML 
    $page_size          = checkInput($_POST["taille"]);
    $orientation        = checkInput($_POST["orientation"]);
    $margin_top         = $_POST["margin_top"];
    $margin_bottom      = $_POST["margin_bottom"];
    $margin_left        = $_POST["margin_left"];
    $margin_right       = $_POST["margin_right"];
    $zoom               = $_POST["zoom"];
    $headerHtml         = $_POST["headerHtml"];
    $footerHtml         = $_POST["footerHtml"];
    $footer_num_page    = checkInput($_POST["footer_num_page"]);

     // Vérifier la saisie de la taille.
    if(empty($_POST["taille"]))
    {
        $taille_err = "Saisissez la Taille s'il vous plait.";     
    }
    else if(empty($_POST["orientation"])){}
    //else if(empty($_POST["taille"])){}
    //else if(empty($_POST["taille"])){}
    else
    {
        // 'Enregistrement des paramétres dans l'API
        $data = array('page_size' =>  $page_size, 'orientation' =>  $orientation, 'margin_top' =>  $margin_top, 'margin_bottom' =>  $margin_bottom, 'margin_left' =>  $margin_left, 'margin_right' =>  $margin_right, 'zoom' =>  $zoom, 'headerHtml' =>  $headerHtml, 'footerHtml' =>  $footerHtml, 'footer_num_page' =>  $footer_num_page);
        if (CallAPI('POST', 'htmltopdf/api/htmlToPdfParametre.php', $data)) {
            $message = "Succès : Enregistrement des paramètres reussis.";
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo $_POST["lien"];
            echo "<script type='text/javascript'>
            window.location = '" . $_SERVER['HTTP_REFERER'] . "';
            </script>";
        } else {
            $message = "Echec : Enregistrement des paramètres échoués.";
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo $_POST["lien"];
            echo "<script type='text/javascript'>
            window.location = '" . $_SERVER['HTTP_REFERER'] . "';
            </script>";
        }
    }

}
//Supression des espaces, antislashes et convertions de tous les caractères prédéfinis en entités HTML sur certaines données du formulaire.
function checkInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
/*----------------------Fin: Récupération des paramètres saisis dans le fomulaire HTML-----------------------------------------*/




/*----------------------Fin: conversion en fichier PDF à partir de l'URL --------------------------------*/

/*----------------------Début: conversion en fichier PDF à partir de l'interface des paramètres --------------------------------*/
$lienEmptyErr = "";
if (isset($_POST["conversion"])) {

    $filename   = $_POST["lien"];
    if (empty($filename)) {
        $message = "Echec !!! : Fichier non converti en PDF, merci de saisir l'url du fichier html";
        echo "<script type='text/javascript'>alert('$message');</script>";
        echo "<script type='text/javascript'>
        window.location = '" . $_SERVER['HTTP_REFERER'] . "';
        </script>";
    } else {
        htmlToPDF($filename);
    }
}

/*----------------------Fin: conversion en fichier PDF à partir de l'URL --------------------------------*/


/*---------------------Début Recuperation de Historique des urls des convertis en PDF ------------------------------------------*/

function getHistoriques()
{

    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, 'htmltopdf/api/htmltopdfhistory.php');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'HTML TO PDF');
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);
    $historiques = json_decode($query, true);
    return $historiques;
}

$historiques = getHistoriques();
/*---------------------Fin Recuperation de Historique des URLs des convertis en PDF ------------------------------------------*/


/*----------------------Début: Enregistrement de l'historique des URLs convertis dans l'API ------------------------------------------*/

function CallAPI($method, $url, $data)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "utlisateur:mot_de_passe");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
/*----------------------FIn : Enregistrement de l'historique des URLs convertis dans l'API ------------------------------------------*/



/*-------------------Début: récuperation des paramètres de conversion en fichier PDF ------------------------------------*/

function getParametres()
{

    $curl_handle = curl_init();
    //curl_setopt($curl_handle, CURLOPT_URL, 'https://testsite.net-profil.com/api/htmlToPdfParametre.php');
    curl_setopt($curl_handle, CURLOPT_URL, 'htmltopdf/api/htmlToPdfParametre.php');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'HTML To PDF');
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

    // Décoder les données JSON
    $parametres = json_decode($query, true);

    // foreach ($parametres as $parametre){
    //     print_r($parametre['page_size']);
    // }
    return $parametres;
}

$parametres     = getParametres();

/*----------------- Début: Fonction de conversion HTML en PDF recuperation des paramétres --------------------------------*/


/*-------------------Début: Fonction de conversion en fichier PDF ------------------------------------*/

function htmlToPDF($filename)
{
    $pathPdfOutFile = 'download.pdf';
    $parametres     = getParametres();
    if ($parametres)
    { 
        foreach ($parametres as $parametre) :
            $page_size       = $parametre['page_size'];
            $orientation     = $parametre['orientation'];
            $margin_top      = $parametre['margin_top'];
            $margin_bottom   = $parametre['margin_bottom'];
            $margin_left     = $parametre['margin_left'];
            $margin_right    = $parametre['margin_right'];
            $zoom            = $parametre['zoom'];
            $headerHtml      = $parametre['headerHtml'];
            $footerHtml      = $parametre['footerHtml'];
            $footer_num_page = $parametre['footer_num_page'];
        endforeach;
        
        
        if(!empty($zoom) || $zoom==null || $zoom=='')
        {
            $zoom = 1;
        }

        if(!empty($margin_top) || $margin_top==null || $margin_top=='')
        {
            $margin_top = 10;
        }
        if(!empty($margin_bottom) || $margin_bottom==null || $margin_bottom=='')
        {
            $margin_bottom = 10;
        }
        if(!empty($margin_left) || $margin_left==null || $margin_left=='')
        {
            $margin_left = 10;
        }
        if(!empty($margin_right) || $margin_right==null || $margin_right=='')
        {
            $margin_right = 10;
        }

        //Si on veut header et footer customiser la saisie des margins est obligatoire
        if($headerHtml && $footerHtml)
        {
            $pdf = new Pdf(array(
                'no-outline',         // Make Chrome not complain
                'disable-smart-shrinking',
                'orientation'   => $orientation,
                'encoding'      => 'UTF-8',
                'margin-top'    => $margin_top,
                'margin-bottom' => $margin_bottom,
                'margin-left'   => $margin_left,
                'margin-right'  => $margin_right,
                'zoom'          => $zoom,
                //'header-html'   => $headerHtml,
                'user-style-sheet'   => $headerHtml,
                'footer-html'   => $footerHtml,
                'viewport-size' => '1280x1024' // this is needed otherwise it will grab the mobile version of your website
            ));
        }
        //Sinon si on veut header customiser la saisie des margins est obligatoire
        elseif($headerHtml)
        {
            $pdf = new Pdf(array(
                'no-outline',         // Make Chrome not complain
                'disable-smart-shrinking',
                'orientation'   => $orientation,
                'encoding'      => 'UTF-8',
                'margin-top'    => $margin_top,
                'margin-bottom' => $margin_bottom,
                'margin-left'   => $margin_left,
                'margin-right'  => $margin_right,
                'zoom'          => $zoom,
                //'header-html'   => $headerHtml,
                'user-style-sheet'   => $headerHtml,
                'viewport-size' => '1280x1024' // this is needed otherwise it will grab the mobile version of your website
            ));
        } 
        //Sinon si on veut footer customiser la saisie des margins est obligatoire
        elseif($footerHtml)
        {
            $pdf = new Pdf(array(
                'no-outline',         // Make Chrome not complain
                'disable-smart-shrinking',
                'orientation'   => $orientation,
                'encoding'      => 'UTF-8',
                'margin-top'    => $margin_top,
                'margin-bottom' => $margin_bottom,
                'margin-left'   => $margin_left,
                'margin-right'  => $margin_right,
                'zoom'          => $zoom,
                'footer-html'   => $footerHtml,
                'viewport-size' => '1280x1024' // this is needed otherwise it will grab the mobile version of your website
            ));
        }   
        //Si c'est une taille par defaut qui est choisit pas de header et footer customiser
        elseif($page_size)
        {
            $pdf = new Pdf(array(
                'no-outline',         // Make Chrome not complain
                'disable-smart-shrinking',
                'page-size'     => $page_size, 
                'orientation'   => $orientation,
                'encoding'      => 'UTF-8',
                'zoom'          => $zoom,
                'footer-center' => $footer_num_page,
                'viewport-size'  => '1280x1024' // this is needed otherwise it will grab the mobile version of your website
            ));
        }
        
    }
    else 
    {
        $message = "Echec !!! : Récupération des paramètres, merci de vérifier accessibilité de l'API ";
        echo "<script type='text/javascript'>alert('$message');</script>";
        echo "<script type='text/javascript'>
        window.location = '" . $_SERVER['HTTP_REFERER'] . "';
        </script>";
    }
    var_dump ($pdf);
    $pdf->addPage($filename);
    if (!$pdf->saveAs($pathPdfOutFile)) {
        echo $pdf->getError();
        //echo ("Echec: PDF no généré");
        $message = "Echec !!! : Fichier non converti en PDF, URL: inaccessible accès bloquée pour les robots ou paramètres invalides ";
        echo "<script type='text/javascript'>alert('$message');</script>";
        echo "<script type='text/javascript'>
        window.location = '" . $_SERVER['HTTP_REFERER'] . "';
        </script>";
        //return false;
    } else {
        //echo ("OK : PDF généré");
        $data = array('lien' =>  $filename);
        CallAPI('POST', 'htmltopdf/api/htmltopdfhistory.php', $data);
       
        readfilePDF($pathPdfOutFile);

   
        $message = "Reussi : Fichier converti en PDF.";
        echo "<script type='text/javascript'>alert('$message');</script>";
        echo $_POST["lien"];
        echo "<script type='text/javascript'>
        window.location = '" . $_SERVER['HTTP_REFERER'] . "';
        </script>";

        //return true;
    }
}

function readfilePDF($pathPdfOutFile)
{
    $file = $pathPdfOutFile;

    $filename = $pathPdfOutFile;
    
    // Header content type
    header('Content-type: application/pdf');
    
    header('Content-Disposition: inline; filename="' . $filename . '"');
    
    header("Content-Type: application/download");

    header('Content-Transfer-Encoding: binary');
    
    header('Accept-Ranges: bytes');
    
    // Read the file
    @readfile($file);


}
/*-------------------Fin: Fonction de conversion en fichier PDF ------------------------------------*/
