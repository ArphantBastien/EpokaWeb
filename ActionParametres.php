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

	try {
		//Si l'utilisateur entre des informations pour le remboursement des Km et des Journées.
		if (isset($_POST['rembourser'])) {
			//Vérifie si des informations existent déjà, sinon prépare une requête insert.
			$stmtVerif = $pdo->prepare("SELECT * FROM param;");
			$stmtVerif->execute();
			$verif = $stmtVerif->fetch();
			if ($verif['prixKm'] == "" || $verif['prixJournee'] == "" ) {
				$stmtInsert = $pdo->prepare("INSERT INTO param (prixKm, prixJournee) VALUES (:insertPrix, :insertHeberg)");
				$stmtInsert->bindParam (":insertPrix", $_POST['km'],PDO::PARAM_STR);
				$stmtInsert->bindParam (":insertHeberg", $_POST['heberg'],PDO::PARAM_STR);
				$stmtInsert->execute();
			}
			//Si oui prépare une requête update.
			else {
			$stmtUpdate = $pdo->prepare("UPDATE param SET prixKm = :prix, prixJournee = :heberg ;");
			$stmtUpdate->bindParam (":prix", $_POST['km'],PDO::PARAM_STR);
			$stmtUpdate->bindParam (":heberg", $_POST['heberg'],PDO::PARAM_STR);
			$stmtUpdate->execute();
			}
		}
	}
	catch (exception $e) {
		die("Erreur de type ".$e->getMessage());
	}

	try {
		////Si l'utilisateur entre des informations pour les distances entre les villes.
		if (isset($_POST['validerdistance'])) {		
			$stmt = $pdo->prepare("INSERT INTO distance (dis_idVilleDepart, dis_idVilleArrivee, dis_km) VALUES (:villedepart, :villearrive, :distance)");
			$stmt->bindParam (":villedepart", $_POST['villes_depart'],PDO::PARAM_STR);
			$stmt->bindParam (":villearrive", $_POST['villes_arrivee'],PDO::PARAM_STR);
			$stmt->bindParam (":distance", $_POST['distance'],PDO::PARAM_STR);
			$stmt->execute();
		}
	}
	catch (exception $e) {
		die("Erreur de type ".$e->getMessage());
	}
		header("Refresh:0;parametres.php");
?>