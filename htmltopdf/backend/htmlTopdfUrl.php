<?php

require __DIR__ . '/../vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf;

// Inclure le fichier config de la base de donnée.
require_once 'db_connect/config.php';


  //Si l'utilisateur s'est authentifie avec une clé de l'api, si oui il le redirige dans la page htmlTopdfUrl.php.
/*---------Vérification de la clé de l'API à partir de l'URL si c'est bon il convertis en PDF --------------------------------*/

// http://htmltopdf/?url=https://www.google.com&license=a893617e7f63711a74f0

 // Vérifier la saisie de l'URL et La clé.
 
 if ($_GET['url'] && $_GET['license']) { 
  
    $license = $_GET['license'];
    //Recherche de l'existance de la licence.

    $sql = 'SELECT id, username, password, license, is_admin FROM users WHERE license = ?';
  
   if ($stmt = $mysql_db->prepare($sql)) {
  
     // Donner les paramètres
     $param_license = $license;
  
       // Bind param to statement stocker
       $stmt->bind_param('s', $param_license);
  
       // Exécuter les requêtes
       if ($stmt->execute()) {
  
         // Stoker les resultats
         $stmt->store_result();
  
         // Vérifier si la license exite o procede à la conversion.
         if ($stmt->num_rows == 1) {
           // stocker les resultats dans les variables
           //$stmt->bind_result($id, $username, $hashed_password, $license);
           htmlToPDF($_GET['url']);
         }
          // Fermeture requêtre
          $stmt->close();
        }
  
        // Fermeture connexion
        $mysql_db->close();
   }
 }
/*-------------------Début: récuperation des paramètres de conversion en fichier PDF ------------------------------------*/

function getParametres()
{

    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, 'https://testsite.net-profil.com/api/htmlToPdfParametre.php');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'HTML To PDF');
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

    // Décoder les données JSON
    $parametres = json_decode($query, true);

    return $parametres;
}

$parametres     = getParametres();

/*----------------- Début: Fonction de conversion HTML en PDF recuperation des paramétres --------------------------------*/


/*-------------------Début: Fonction de conversion en fichier PDF ------------------------------------*/
function htmlToPDF($filename)
{
    //echo 'url:'.$filename;
    //$pathPdfOutFile = '/home/aicha/Téléchargements/htmltopdf.pdf'; 
    $pathPdfOutFile = "download.pdf";
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

    $pdf->addPage($filename);
    if (!$pdf->saveAs($pathPdfOutFile)) {
        //echo $pdf->getError();
        echo ("Echec: PDF no généré");
        //return false;
    } else {
        //echo ("OK : PDF généré");
        $data = array('lien' =>  $filename);
        CallAPI('POST', 'https://testsite.net-profil.com/api/htmltopdfhistory.php', $data);
        
        //enregistrer le fichier sur le pc

        readfilePDF($pathPdfOutFile);

        //return true;
    }
}



//enregistrer le fichier sur le pc
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


/*-------------------Fin: Fonction de conversion en fichier PDF ------------------------------------*/
