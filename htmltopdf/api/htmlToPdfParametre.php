<?php
	// Connexion sur la base
	//include("db_connect.php");
	require_once '../db_connect/config.php';

	$request_method = $_SERVER["REQUEST_METHOD"];
	function getHtmlToPdfParametres()
	{
		global $conn;
		$query = "SELECT * FROM parametre";
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function getHtmlToPdfParametre($id=0)
	{
		global $conn;
		$query = "SELECT * FROM parametre";
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
	
	function AddHtmlToPdfParametre()
	{
		global $conn;
		$page_size   		= $_POST["page_size"];
		$orientation 		= $_POST["orientation"];
		$margin_top         = $_POST["margin_top"];
		$margin_bottom      = $_POST["margin_bottom"];
		$margin_left        = $_POST["margin_left"];
		$margin_right       = $_POST["margin_right"];
		$zoom        		= $_POST["zoom"];
		$headerHtml         = $_POST["headerHtml"];
		$footerHtml         = $_POST["footerHtml"];
		$footer_num_page    = $_POST["footer_num_page"];
		$data = json_decode(file_get_contents("php://input"));
		$created = date('Y-m-d H:i:s');
		

		$query = "DELETE FROM parametre;";
		if(mysqli_query($conn, $query))
		{
			echo $query="INSERT INTO parametre (page_size, orientation, margin_top, margin_bottom, margin_left, margin_right, zoom, headerHtml, footerHtml, footer_num_page, created) VALUES('".$page_size."', '".$orientation."', '".$margin_top."', '".$margin_bottom."', '".$margin_left."', '".$margin_right."', '".$zoom."', '".$headerHtml."', '".$footerHtml."', '".$footer_num_page."',  '".$created."')";
			
			if(mysqli_query($conn, $query))
			{
				$response=array(
					'status' => 1,
					'status_message' =>'Données ajouté avec succés dans les paramètres.'
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
	}
	
	//parametres
	function updateHtmlToPdfParametre($id)
	{
		global $conn;
		$_PUT = array();
		parse_str(file_get_contents('php://input'), $_PUT);
		$page_size 		= $_PUT["page_size"];
		$orientation 	= $_PUT["orientation"];
		$margin_top 	= $_PUT["margin_top"];
		$margin_bottom 	= $_PUT["margin_bottom"];
		$margin_left 	= $_PUT["margin_left"];
		$margin_right 	= $_PUT["margin_right"];
		$zoom 			= $_PUT["zoom"];
		$modified = date('Y-m-d H:i:s');
		echo "page_size".$page_size;

		if($id != null)
			$query="UPDATE parametre SET page_size='".$page_size."', orientation='".$orientation."', margin_top='".$margin_top."', margin_bottom='".$margin_bottom."', margin_left='".$margin_left."', margin_right='".$margin_right."', zoom='".$zoom."', modified='".$modified."' WHERE id=".$id;
		else
			AddHtmlToPdfParametre();

		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Données mises à jour avec succés dans les paramètres.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Echec de la mise à jour des paramètres. '. mysqli_error($conn)
			);
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	
	function deleteHtmlToPdfParametre($id)
	{
		global $conn;
		$query = "DELETE FROM parametre WHERE id=".$id;
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Les données des paramètres sont supprimées avec succés.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'La suppression dans les paramètres a echoué. '. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	switch($request_method)
	{
		
		case 'GET':
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getHtmlToPdfParametre($id);
			}
			else
			{
				getHtmlToPdfParametres();
			}
			break;
		default:
			// Methode de Requête Invalide
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
		case 'POST':
			// Ajouter les paramètres dans la base
			AddHtmlToPdfParametre();
			break;
			
		case 'PUT':
			// Modifier les paramètres dans la base.
			$id = intval($_GET["id"]);
			updateHtmlToPdfParametre($id);
			break;
			
		case 'DELETE':
			// Supprimer les paramètres dans la base.
			$id = intval($_GET["id"]);
			deleteHtmlToPdfParametre($id);
			break;

	}


?>