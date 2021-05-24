<?php
  // Initialiser la session.
  session_start();
  // Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion.
  if(!isset($_SESSION["numero"])){
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title></title>
    <link rel="stylesheet" href="styleco.css" />
  </head>
  <body id="bodyadmin">
    <header>
    <a href="logout.php"id="stylebarre" >Déconnexion</a>
    <a href="requetes.php"id="stylebarre">Validation des missions</a>
    <a href="payer.php" id="stylebarre">Paiement des frais</a>
    <a href="requetes.php"id="stylebarre">Paramétrage</a>
    </header>
    <div id="vueadmin" class="sucess">
        <p style="font-size: 70px; padding-bottom: 10%; text-align: center;">VOUS ETES BIEN CONNECTE</p>
    </div>
  </body>
</html>