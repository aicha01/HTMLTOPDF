<?php
// Initialiser la session
session_start();
 
// Vérifier si l'utilisateur est authentifier, si non il est rediriger vers la page d'authentification.
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true)
{
    header('location: /home');
    exit;
}
 
// Inclure le fichier config.
require_once 'db_connect/config.php';
 
// Définir les variables et initialiser avec des valuers vides.
$new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';
 
// Processing du formulaure quand la formulaire est soumis.
if($_SERVER['REQUEST_METHOD'] == 'POST'){
 
    // Valider le nouveau mot de passe. 
    if(empty(trim($_POST['new_password']))){
        $new_password_err = 'Entrer le nouveau mot passe s\'il vous plait.';     
    } elseif(strlen(trim($_POST['new_password'])) < 6){
        $new_password_err = 'Le mot de passe doit convenir au minimum 6 caractères.';
    } else{
        $new_password = trim($_POST['new_password']);
    }
    
    // Valider la confirmation du nouveau mot de passe.
    if(empty(trim($_POST['confirm_password']))){
        $confirm_password_err = 'Confirmer le mot passe s\'il vous plait.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = 'Les mots de passe ne sont pas identiques.';
        }
    }
        
    // Vérifier les erreurs des valeurs saisis avant mis à jour des données.
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Preparer la requête de mise à jour.
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        
        if($stmt = $mysql_db->prepare($sql)){
            // Mettre les paramètres.
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Mettre les variables dans la requête préparée.
            $stmt->bind_param("si", $param_password, $param_id);
            
            
            // Exécuter la requête préparée.
            if($stmt->execute()){
                // Mise à jour du mot de passe reussit. Desctruction de la session, et redirection vers la page d'authentification.
                session_destroy();
                header("location: /");
                exit();
            } else{
                echo "Oops! Quelque chose a échoué, merci de rééssayer plutart.";
            }

            // Fermer les requête.
            $stmt->close();
        }

        // Fermer la connexion.
        $mysql_db->close();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
    <style type="text/css">
        .wrapper{ 
            width: 500px; 
            padding: 20px; 
        }
        .wrapper h2 {text-align: center}
        .wrapper form .form-group span {color: red;}
    </style>
</head>
<body>
    <main class="container wrapper">
        <section>
            <h2>Changement Mot de Passe</h2>
            <p>Merci de renseigner votre nouveau mot de passe.</p>
            <form action="" method="post"> 
                <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirmer Nouveau mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-primary" value="Changer">
                    <a class="btn btn-block btn-link bg-light" href="../index.php">Annuler</a>
                </div>
            </form>
        </section>
    </main> 
    <div class="container">
        <br />
        Copyright ©Net Profil
    </div>   
</body>

</html>