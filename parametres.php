<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["numero"])) {
	header("Location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Paramètres Epoka</title>
	<link rel="stylesheet" href="stylelog.css" />
</head>
<body id="bodyadmin">
<header>
    <a href="logout.php">Déconnexion</a>
    <a href="requetes.php">Validation des missions</a>
    <a href="payer.php">Paiement des frais</a>
    <a href="requetes.php">Paramétrage</a>
    </header>
	<div style="margin-bottom: 2%;">
		<p id="titre">Paramétrage de l'application</p>
	</div>
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
$pdo = new PDO("mysql:host=$server;dbname=$base;charset=utf8", $username, $password);
$connexion = new mysqli($server, $username, $password, $base);
$finaldate = "";

//Afficher remboursement km et indémnités
$stmt = $pdo->prepare('SELECT * FROM param');
$stmt->execute();
$km = $stmt->fetch();

//Afficher villes
$stmt2 = $pdo->prepare('SELECT * FROM ville ORDER BY vil_categorie, vil_nom');
$stmt2->execute();
$villes = $stmt2->fetchAll();

//Afficher distances
?>
	<p id="titre2">Montant du remboursement au km</p>
	<table class="container" id="containerparam">
		<form class="box" action="ActionParametres.php" method="post" name="remboursement">
			<tr>
				<td>
					<p>Remboursement au Km : </p>
				</td>
				<td> <input type="text" name="km" value="<?php echo ($km['prixKm']); ?>"></td>
			</tr>
			<tr>
				<td>
					<p>Indemnité d'hébergement : </p>
				</td>
				<td><input type="text" name="heberg" value="<?php echo ($km['prixJournee']); ?>"></td>
			</tr>
	</table>
	<input type="submit" name="rembourser" value="Valider">
	</form>
	</table>
	</br></br></br><p id="titre2">Distance entre villes</p>
	<table class="container" id="containerparam">
		<form class="box" action="ActionParametres.php" method="post" name="distances">
			<tr>
				<td>
					<p>De <select name="villes_depart">
							<?php
							foreach ($villes as $ville) {
								echo ('<option value="' . $ville['vil_id'] . '">' . $ville['vil_nom'] . '</option>');
							}
							?>
						</select>
				</td>
				<td>à <select name="villes_arrivee">
						<?php
						foreach ($villes as $ville) {
							echo ('<option value="' . $ville['vil_id'] . '">' . $ville['vil_nom'] . '</option>');
						}
						?>
					</select></td>
				<td>Distance en km : </td>
				<td><input type="text" name="distance" placeholder="Renseignez une distance"></td>
				</p>
				</br></tr>
	</table>
	<input type="submit" name="validerdistance" value="Valider">
	</form>
	</br>
	<p id="titre2">Distance entre villes déjà saisies</p>
	<div id="bloctextadmin">
		<?php
		//Envoi d'une requête SQL pour afficher les données des visiteurs de la base de données.
		try {
			$stmt = $pdo->prepare("SELECT dis_km, a.vil_nom as ville1, b.vil_nom as ville2 FROM distance d JOIN ville a ON d.dis_idVilleDepart =a.vil_id JOIN ville b ON d.dis_idVilleArrivee = b.vil_id ORDER BY dis_km DESC");
			$stmt->execute();
			//Affichage des données dans un tableau avec un style en CSS.
			echo ('<table class="container" id="containerparam">
						<thead>
							<tr>
								<th><h1>De</h1></th>
								<th><h1>A</h1></th>
								<th><h1>Km</h1></th>
							</tr>
						</thead>
						<tbody>');
			foreach ($stmt->fetchAll() as $ligne) {

				//Affiche le résultat de la requête SQL ligne par ligne.
				echo ('<tr><td>' . $ligne["ville1"] . '</td>
					<td>' . $ligne["ville2"] . '</td>
					<td>' . $ligne["dis_km"] . '</td></tr>');
			}
			echo ('</tbody></table>');
		} catch (exception $e) {
			die("Erreur de type " . $e->getMessage());
		}
	}
		?>
	</div>
</body>

</html>