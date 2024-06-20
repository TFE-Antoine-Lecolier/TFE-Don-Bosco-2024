<?php
session_start();
// Détruire uniquement les données de session de l'administrateur
unset($_SESSION['user_role']);
// Rediriger vers la page de connexion de l'administrateur
header("Location: ../index.php");
exit;
?>
