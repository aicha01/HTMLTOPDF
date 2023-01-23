<?php
// Initialisation des sessions
session_start();

// Inclure le fichier config de la base de donnée.
require_once 'db_connect/config.php';

/****************  1ere cas : déjà authentifier sur l'application ****************/

// Vérifier si l'utilisateur s'est authentifier, si oui il le redirige dans la page de conversion. 
  if((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true))
  {
    header("location: /home");
    exit;
  }


/**************** 3eme cas: Authentication par le login et mot de passe ****************/

  // Definier les variable et initialiser les valeurs à vide
  $username = $password = '';
  $username_err = $password_err = '';

  // Processus submission des données du formulaire.
  if ($_SERVER['REQUEST_METHOD'] === 'POST') 
  {

    // Vérifier que le login ou l'email pas vide
    if(empty(trim($_POST['username']))){
      $username_err = 'Entrer le login ou l\'email s\'il vous plait.';
    } else{
      $username = trim($_POST['username']);
    }

    // Vérifier de le mot de passe n'est pas vide. 
    if(empty(trim($_POST['password']))){
      $password_err = 'Entrer le mot passe s\'il vous plait.';
    } else{
      $password = trim($_POST['password']);
    }
    //var_dump($username.''.$password);
    // Valider les credentials
    if (empty($username_err) && empty($password_err)) {
      // Preparer une requête select
      $sql = 'SELECT id, username, password, license, is_admin FROM users WHERE username = ?';

      if ($stmt = $mysql_db->prepare($sql)) {

        // Donner les parametres
        $param_username = $username;

        // Bind param to statement stocker
        $stmt->bind_param('s', $param_username);

        // Exécuter les requêtes
        if ($stmt->execute()) {

          // Stoker les resultats
          $stmt->store_result();

          // Vérifier si le login ou l'email exite.
          if ($stmt->num_rows == 1) {
            // stocker les resultats dans les variables
            $stmt->bind_result($id, $username, $hashed_password, $license, $is_admin);

            if ($stmt->fetch()) {
              if (password_verify($password, $hashed_password)) {

                // Commencer une nouvelle session
                session_start();

                // Enregister les données dans la sessions
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['license'] = $license;
                $_SESSION['is_admin'] = $is_admin;
                // Rediriger vers la page de conversion html to pdf
                header('location: /home');
              } 
              else {
                // Afficher une erreur si les mots de passe ne correspondent pas.
                $password_err = 'Mot de passe invalide';
              }
            }
          } else {
            $username_err = "Le Login ou l'email n'existe pas.";
          }
        } else {
          echo "Oops! Quelque chose à échoué, merci de réessayer.";
        }
        // Fermeture requêtre
        $stmt->close();
      }

      // Fermeture connexion
      $mysql_db->close();
    }
  }
?>
