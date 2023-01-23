<?php
	// Connexion sur la base.
	//include("db_connect.php");

	require_once '../db_connect/config.php';

	$request_method = $_SERVER["REQUEST_METHOD"];
	function getHtmlToPdfHistorys()
	{
		global $conn;
		$query = "SELECT * FROM historique WHERE lien<>'' ORDER BY created DESC LIMIT 100";
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function getHtmlToPdfHistory($id=0)
	{
		global $conn;
		$query = "SELECT * FROM historique";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function AddHtmlToPdfHistory()
	{
		global $conn;
		$lien = $_POST['lien'];
		$data = json_decode(file_get_contents("php://input"));
		//$lien = $data->lien;
		$created = date('Y-m-d H:i:s');
		
			echo $query="INSERT INTO historique(lien, created) VALUES('".$lien."', '".$created."')";
			if(mysqli_query($conn, $query))
			{
				$response=array(
					'status' => 1,
					'status_message' =>'Lien ajouté avec succés sur l\'historique.'
				);
			}
			else
			{
				$response=array(
					'status' => 0,
					'status_message' =>'ERREUR!.'. mysqli_error($conn)
				);
			}
			header('Content-Type: application/json');
			echo json_encode($response);		
		
	}
	
	function updateHtmlToPdfHistory($id)
	{
		global $conn;
		$_PUT = array();
		parse_str(file_get_contents('php://input'), $_PUT);
		$lien = $_PUT["lien"];
		$created = 'NULL';
		$query="UPDATE historique SET lien='".$lien."' WHERE id=".$id;
		
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Lien mis a jour avec succes su l\'historique.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Echec de la mise à jour de l\'historique des liens. '. mysqli_error($conn)
			);
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function deleteHtmlToPdfHistory($id)
	{
		global $conn;
		$query = "DELETE FROM historique WHERE id=".$id;
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'L\'historique du lien supprimé avec succés.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'La suppression dans l\'historique du lien a echoué. '. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	switch($request_method)
	{
		
		case 'GET':
			// Retrive HtmlToPdfHistorys
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getHtmlToPdfHistory($id);
			}
			else
			{
				getHtmlToPdfHistorys();
			}
			break;
		default:
			// Methode de Requête Invalide
			header("HTTP/1.0 405 Requête Pas Autorisé");
			break;
			
		case 'POST':
			// Ajouter un lien dans l'historique
			AddHtmlToPdfHistory();
			break;
			
		case 'PUT':
			// Modifier un line dans l'historique
			$id = intval($_GET["id"]);
			updateHtmlToPdfHistory($id);
			break;
			
		case 'DELETE':
			// Supprimer un lien dans l'historique
			$id = intval($_GET["id"]);
			deleteHtmlToPdfHistory($id);
			break;

	}


?>