<?php
session_start();

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Rediriger vers la page d'accueil après connexion réussie
header("Location: ../index.php");
exit;
?>
