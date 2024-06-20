<?php
session_start(); // Assurez-vous que la session est démarrée

// Inclure le fichier de connexion
include '../config.php';

// Fonction pour enregistrer une action dans les logs
function logAction($conn, $user_id, $component_name, $action) {
    $current_time = date('Y-m-d H:i:s'); // Obtenir la date et l'heure actuelles
    $log_query = "INSERT INTO component_logs (user_id, component_name, date_added, action) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($log_query)) {
        $stmt->bind_param("isss", $user_id, $component_name, $current_time, $action);
        if ($stmt->execute()) {
            echo "Action logged successfully";
        } else {
            echo "Erreur lors de l'exécution de la requête de journalisation: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Erreur lors de la préparation de la requête de journalisation: " . $conn->error);
    }
}

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    // Vérifier que l'ID est valide
    if ($id > 0) {
        // Récupérer le nom du composant avant la mise à jour
        $component_query = "SELECT name FROM components WHERE id = ?";
        if ($stmt = $conn->prepare($component_query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($component_name);
            $stmt->fetch();
            $stmt->close();
        } else {
            die("Erreur lors de la préparation de la requête de récupération du composant: " . $conn->error);
        }

        if ($action == 'increase') {
            $sql = "UPDATE components SET stock = stock + 1 WHERE id = $id";
            $log_action = 'Ajout Du Stock';
        } elseif ($action == 'decrease') {
            $sql = "UPDATE components SET stock = stock - 1 WHERE id = $id";
            $log_action = 'Diminution Du Stock';
        }

        if ($conn->query($sql) === TRUE) {
            echo "Stock mis à jour avec succès.";
            // Enregistrer l'action de mise à jour du stock dans les logs
            if (isset($_SESSION['user_id'])) {
                logAction($conn, $_SESSION['user_id'], $component_name, $log_action);
            } else {
                echo "User ID de la session non défini.";
            }
        } else {
            echo "Erreur lors de la mise à jour du stock: " . $conn->error;
        }
    } else {
        echo "ID invalide.";
    }
} else {
    echo "Paramètres manquants.";
}

// Fermer la connexion à la base de données
$conn->close();

// Redirection vers la page précédente
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
