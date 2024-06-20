<?php
session_start();
// Inclure le fichier de connexion
include '../config.php';

// Définir le fuseau horaire à Paris, France
date_default_timezone_set('Europe/Paris');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../index.php');
    exit;
}

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['size']) && isset($_POST['stock'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $size = $conn->real_escape_string($_POST['size']);
    $stock = intval($_POST['stock']);

    // Requête SQL pour mettre à jour les informations du composant
    $sql = "UPDATE components SET name = '$name', size = '$size', stock = $stock WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Journaliser l'action de mise à jour du composant
        $action = 'Mise à jour du composant';
        $date_added = date('Y-m-d H:i:s');
        $log_sql = "INSERT INTO component_logs (user_id, component_name, action, date_added) VALUES (?, ?, ?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("isss", $user_id, $name, $action, $date_added);
        if ($log_stmt->execute()) {
            echo "Composant mis à jour avec succès et journalisé.";
        } else {
            echo "Composant mis à jour mais erreur lors de la journalisation: " . $log_stmt->error;
        }
    } else {
        echo "Erreur lors de la mise à jour du composant: " . $conn->error;
    }
} else {
    echo "Paramètres manquants.";
}

// Fermer la connexion à la base de données
$conn->close();

// Redirection vers la page d'accueil
header("Location: ../accueil.php");
exit();
?>
