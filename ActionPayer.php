<?php
		// Initialiser la session
		session_start();
		// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
		if(!isset($_SESSION["numero"])){
  		header("Location: index.php");
  		exit(); 
		}

		//Requête SQL (appelée depuis le fichier requetes.php) supprimant les données d'un visiteurs dans la base de données en fonction de son addresse courriel.
		$server = "localhost";
		$base = "epoka";
		$username = "root";
		$password = "";
		$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username, $password);
		$stmt = $pdo->prepare("UPDATE mission SET mis_paiement = 1 WHERE mis_id = ".$_POST['payer'].";");
		$stmt->execute();
		header("Refresh:0;payer.php");
?>