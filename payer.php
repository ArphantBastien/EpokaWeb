<?php
  // Initialiser la session
  session_start();
  // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
  if(!isset($_SESSION["numero"])){
    header("Location: index.php");
    exit(); 
  }
?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">	
			<title>PAIEMENT DES FRAIS</title>
			<link rel="stylesheet" href="stylelog.css" />
		</head>
		<body id="bodyadmin">
		<header>
    <a href="logout.php">Déconnexion</a>
    <a href="requetes.php">Validation des missions</a>
    <a href="payer.php">Paiement des frais</a>
    <a href="parametres.php">Paramétrage</a>
    </header>
			<div style="margin-bottom: 2%;">
				<p id="titre1">PAIEMENT DES FRAIS</p>
			</div>
			<div id="bloctextadmin">
			<?php
			if ($_SESSION['rembourser'] != 1) {
				echo("<p id='error'>Vous n'êtes pas autorisé à accéder à cette page!<p>");
			}
			else {
			//Déclaration des variables PHP.
			$server = "localhost";
			$base = "epoka";
			$username = "root";
			$password = "";
			$table = "rdv_jpo";
			$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username , $password);
			$connexion = new mysqli($server, $username, $password, $base);
			$finaldate = "";

			//Envoi d'une requête SQL pour afficher les données des visiteurs de la base de données.
			try {
				$stmt = $pdo->prepare("SELECT *, DATEDIFF(mis_dateFin, mis_dateDebut) as nbjour FROM salarie, mission, ville WHERE mis_validation = 1 AND mis_idSalarie = sal_id AND mis_idDestination = vil_id ORDER BY mis_dateDebut");
				$stmt->execute();
				//Affichage des données dans un tableau avec un style en CSS.
				echo ('<table class="container">
						<thead>
							<tr>
							<th><h1>Nom du salarié</h1></th>
							<th><h1>Prénom du salarié</h1></th>
							<th><h1>Début de la mission</h1></th>
							<th><h1>Fin de la mission</h1></th>
							<th><h1>Lieu de la mission</h1></th>
							<th><h1>Montant</h1></th>
							<th><h1>Paiement</h1></th>
							</tr>
						</thead>
						<tbody>');
				foreach ($stmt->fetchAll() as $ligne) {

						//Calcule du montant à rembourser.
						$stmtville1 = $pdo->prepare("SELECT age_ville FROM agence JOIN ville ON age_ville = vil_id JOIN salarie ON sal_idAgence = age_id WHERE age_id = :idagence");
						$stmtville1->bindParam (":idagence", $_SESSION["numero"],PDO::PARAM_STR);
						$stmtville1->execute();
						$ville1=$stmtville1->fetch();
						$ville1 = $ville1['age_ville'];
						$ville2 = $ligne['mis_idDestination'];

						//Trouve la distance entre la ville de départ et d'arrivée.
						$stmtdistance = $pdo->prepare("SELECT dis_km FROM distance WHERE (dis_idVilleDepart = ".$ville1." AND dis_idVilleArrivee = ".$ville2.") OR (dis_idVilleDepart = ".$ville2." AND dis_idVilleArrivee = ".$ville1.")");
						$stmtdistance->execute();
						$distance = $stmtdistance->fetch();

						//Trouver paramètres
						$stmtparam = $pdo->prepare("SELECT * FROM param");
						$stmtparam->execute();
						$param = $stmtparam->fetch();
						$montantKm = $param['prixKm'];
						$montantJ = $param['prixJournee'];

						//Vérification et calculre montant mission.
						$stmtSelectMontant = $pdo->prepare("SELECT mis_montant FROM mission");
						$stmtSelectMontant->execute();
						$selectMontant = $stmtSelectMontant->fetch();

						if($distance == "") {
							$montant = "La distance n'est pas renseignée";
						}
						else if($selectMontant['mis_montant'] == "") {
							$montant = (($distance['dis_km']*2)*$montantKm)+(($ligne['nbjour']+1)*$montantJ);
						}
						else {
							$montant = $selectMontant['mis_montant'];
						}
						//Affiche le boutton payer si la mission n'esst pas payer et que la distance est bien renseignée
						if (($ligne['mis_paiement'] == 0) && ($distance != "")) {
							$validation = '<td>
							<form action="ActionPayer.php" method="post">
							<button value="'.$ligne["mis_id"].'" name="payer" type="submit" >PAYER</button>
							</form>';
						}
						// si la distance esst nulle 
						else if ($distance == ""){
						// on affiche rien dans la collonne
							$validation = '<td>';
						}
						else {
							$validation = '<td>Remboursée';
						}						
					//Affiche le résultat de la requête SQL ligne par ligne.
					echo ('<tr><td>' . $ligne["sal_nom"] . '</td>
                    <td>' . $ligne["sal_prenom"] . '</td>
                    <td>' . $ligne["mis_dateDebut"] . '</td>
                    <td>' . $ligne["mis_dateFin"] . '</td>
                    <td>' . $ligne["vil_nom"] . ' ('. $ligne["vil_cp"] .')
                    <td>'.$montant.'</td>
                    </td>'.$validation.'</tr>');
				}
				echo ('</tbody></table>');
			}
			catch (exception $e) {
				die("Erreur de type ".$e->getMessage());
			}
		}
			?>
			</div>
		</body>
</html>