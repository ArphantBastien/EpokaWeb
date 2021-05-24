<!DOCTYPE html>
<html lang="fr">
<head>
    <title>log</title>
  <link rel="stylesheet" href="stylelog.css" />
</head>
<body id="bodyadmin">
<?php
require('config.php');
session_start();
if (isset($_POST['username'])){
  $username = stripslashes($_REQUEST['username']);
  $password = stripslashes($_REQUEST['password']);
  $stmt = $pdo->prepare ("SELECT * FROM salarie WHERE sal_id=:user AND sal_mdp=PASSWORD(:mdp)");
  $stmt->bindParam (":user", $username,PDO::PARAM_STR);
  $stmt->bindParam (":mdp", $password,PDO::PARAM_STR);
  $stmt->execute ();

  if ($ligne = $stmt->fetch()){
      $_SESSION['numero'] = $username;
      $_SESSION['valider'] = $ligne['sal_peutValider'];
      $_SESSION['rembourser'] = $ligne['sal_peutPayer'];
      header("Location: connecte.php");
  } else {
    $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
  }
}
?>
	<header class="anope">
    <img src="imagecle" id="cle">
    <a>Validation des missions</a>
    <a>Paiement des frais</a>
    <a>Paramétrage</a>
  </header>
  <div id="container">
<form class="box" action="" method="post" name="login">
<h1 class="box-title">CONNECTE-TOI</h1>
<input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur">
<input type="password" class="box-input" name="password" placeholder="Mot de passe">
<input type="submit" value="suivant" name="submit" class="box-button">
<?php if (! empty($message)) { ?>
    <p class="errorMessage"><?php echo $message; ?></p>
<?php } ?>
</form>
</div>
</body>
</html>