<?php
// Connexion à la base de données MySQL.
$pdo = new PDO("mysql:host=localhost; dbname=epoka;charset=UTF8", "root", "");
 
// Vérifier la connexion.
if($pdo === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
?>