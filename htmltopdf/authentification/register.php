<?php
	// Inclure le fichier config de la base 
	require_once 'db_connect/config.php';


	// Definir les variables et initialisation ave des valeurs vides.
	$username = $password = $confirm_password = "";

	$username_err = $password_err = $confirm_password_err = "";

	// Processus de soumission des données du formulaire.
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Vérifier si le login ou l'email est vide.
		if (empty(trim($_POST['username']))) {
			$username_err = "Entrer le login ou l'email s'il vous plait.";

			// Verifier si le login ou l'email exite déjà.
		} else {

			// Preparer une requête select.
			$sql = 'SELECT id FROM users WHERE username = ?';

			if ($stmt = $mysql_db->prepare($sql)) {
				// mettre le parametre.
				$param_username = trim($_POST['username']);

				// Metre les parametres des variables pour préparer la requête
				$stmt->bind_param('s', $param_username);

				// Exécuter la requête.
				if ($stmt->execute()) {
					
					// Stocker le resultat exécuté.
					$stmt->store_result();

					if ($stmt->num_rows == 1) 
					{
						$username_err = 'Ce login ou cet email est déjà prise, merci de rééssayer avec un autre';
					} 
					else 
					{
						$username = trim($_POST['username']);
					}
				} else {
					echo "Oops! ${$username}, quelque à échoué. Merci de rééssayer plutart.";
				}

				// Fermer la requête
				$stmt->close();
			} else {

				// Fermer la connexion.
				$mysql_db->close();
			}
		}

		// Valider le mot de passe.
		if(empty(trim($_POST["password"]))){
	        $password_err = "Entrer le mot passe s'il vous plait.";     
	    } elseif(strlen(trim($_POST["password"])) < 6){
	        $password_err = "Le mot de passe doit convenir au minimum 6 caractères.";
	    } else{
	        $password = trim($_POST["password"]);
	    }
    
	    // Valider la confirmation du mot de passe.
	    if(empty(trim($_POST["confirm_password"]))){
	        $confirm_password_err = "Confirmer le mot passe s'il vous plait.";     
	    } else{
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($password_err) && ($password != $confirm_password)){
	            $confirm_password_err = "Les mots de passe ne sont pas identiques.";
	        }
	    }

	    // Vérifier les erreurs de saisie avant d'inserer dans la base.

	    if (empty($username_err) && empty($password_err) && empty($confirm_err)) {

	    	// Preparer la requête insert (insertion).
			//$sql = 'INSERT INTO users (username, password) VALUES (?,?)';
			$sql = 'INSERT INTO users (username, password, license, is_admin) VALUES (?,?,?,?)';

			if ($stmt = $mysql_db->prepare($sql)) {
				//Générer un api key
				$bytes = random_bytes(10);
				$license = bin2hex($bytes);
				// Mettre les parametres.

				//la création des users par défaut ils ne sont pas administrateur.
				$is_admin = trim($_POST["is_admin"]);	

				$param_username = $username;
				$param_license = $license;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Créer un mot de passe
				$param_is_admin = $is_admin;

				// Mettre les parametres des variables dans la requête.
				//$stmt->bind_param('ss', $param_username, $param_password);
				$stmt->bind_param('ssss', $param_username, $param_password, $param_license, $param_is_admin);

				// Exécuter la requête
				if ($stmt->execute()) {
					// Redirection vers la page d'authentification.
					header('location: /');
					// echo "vas rediriger vers la page d'authentification";
				} else {
					echo "Oops! Quelque chose a échoué, merci de rééssayer plutart";
				}

				// Fermer la requête
				$stmt->close();	
			}

			// Fermer la connexion
			$mysql_db->close();
	    }
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Inscription</title>
	<link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
	<style>
        .wrapper{ 
        	width: 500px; 
        	padding: 20px; 
        }
        .wrapper h2 {text-align: center}
        .wrapper form .form-group span {color: red;}
	</style>
</head>
<body>
	<main>
		<section class="container wrapper">
			<h2 class="display-5 pt-4">Création utilisateur</h2>
        	<p class="text-center">Merci de renseigner les identifiants.</p>
        	<form action="" method="POST">
        		<div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
        			<label for="username">Login ou email</label>
        			<input type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
        			<span class="help-block"><?php echo $username_err;?></span>
        		</div>

        		<div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
        			<label for="password">Saisissez le mot de passe</label>
        			<input type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
        			<span class="help-block"><?php echo $password_err; ?></span>
        		</div>

        		<div class="form-group <?php (!empty($confirm_password_err))?'has_error':'';?>">
        			<label for="confirm_password">Confirmer votre mot de passe</label>
        			<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
        			<span class="help-block"><?php echo $confirm_password_err;?></span>
        		</div>

				<div class="form-group">
        			<label for="is_admin">Administrateur</label>&nbsp; &nbsp;
        			<input type="checkbox" name="is_admin" id="is_admin" value="1">
        		</div>

        		<div class="form-group">
        			<input type="submit" class="btn btn-block btn-outline-success" value="Inscription">
        			<input type="reset" class="btn btn-block btn-outline-primary" value="Mot de passe oublié">
        		</div>
				<!--<p>Déjà un compte? <a href="../index.php">S'authentifier ici</a>.</p>--> 
				<p>Convertir HTML en PDF? <a href="../htmltopdf.php">Page Conversion</a>.</p> 
				</form>
		</section>
	</main>
	<div class="container">
        <br />
        Copyright ©Net Profil
    </div>
</body>
</html>