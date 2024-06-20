<?php
// config.php

$host = 'localhost';
$user = 'root'; // Remplacez par votre nom d'utilisateur MySQL
$pass = 'Qwerty123?'; // Remplacez par votre mot de passe MySQL
$db = 'gestion_stock'; // Nom de votre base de données

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
