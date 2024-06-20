<?php
session_start();
include '../config.php';
include '../phpqrcode/qrlib.php'; // Inclure la bibliothèque QR Code

// Définir le fuseau horaire à Paris, France
date_default_timezone_set('Europe/Paris');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['name'], $_POST['size'], $_POST['stock'])) {
    $name = $_POST['name'];
    $size = $_POST['size'];
    $stock = intval($_POST['stock']);

    // Requête SQL avec déclaration préparée pour insérer un nouveau composant
    $sql = "INSERT INTO components (name, size, stock, qr_code_path) VALUES (?, ?, ?, '')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $name, $size, $stock);
        if ($stmt->execute()) {
            $last_id = $stmt->insert_id; // Récupérer l'ID du composant inséré

            // Générer le QR code avec l'URL de la page de protection par mot de passe
            $base_url = 'http://tfe-inventory.duckdns.org'; // Remplacez par votre domaine
            $qr_text = "$base_url/component/password_protect.php?id=$last_id";
            $qr_file = '../qrcodes/' . $last_id . '.png';
            if (!file_exists('../qrcodes')) {
                if (!mkdir('../qrcodes', 0777, true)) {
                    die("Erreur lors de la création du répertoire des QR codes.");
                }
            }

            QRcode::png($qr_text, $qr_file);

            // Vérifiez que le fichier QR code a été créé
            if (file_exists($qr_file)) {
                // Mettre à jour l'entrée avec le chemin du QR code
                $update_sql = "UPDATE components SET qr_code_path = ? WHERE id = ?";
                if ($stmt_update = $conn->prepare($update_sql)) {
                    $stmt_update->bind_param("si", $qr_file, $last_id);
                    if ($stmt_update->execute()) {
                        // Journaliser l'ajout du composant avec l'action correspondante
                        $action = "Création du composant"; // Définir l'action ici
                        $date_added = date('Y-m-d H:i:s');
                        $log_sql = "INSERT INTO component_logs (user_id, component_name, action, date_added) VALUES (?, ?, ?, ?)";
                        if ($log_stmt = $conn->prepare($log_sql)) {
                            $log_stmt->bind_param("isss", $user_id, $name, $action, $date_added);
                            if ($log_stmt->execute()) {
                                echo "Nouveau composant ajouté avec succès avec QR code.";
                                header("Location: ../accueil.php"); // Redirige vers la page d'accueil après l'ajout
                                exit();
                            } else {
                                die("Erreur lors de la journalisation de l'ajout du composant : " . $log_stmt->error);
                            }
                        } else {
                            die("Erreur lors de la préparation de la requête de journalisation : " . $conn->error);
                        }
                    } else {
                        die("Erreur lors de la mise à jour du QR code: " . $stmt_update->error);
                    }
                } else {
                    die("Erreur lors de la préparation de la requête de mise à jour du QR code: " . $conn->error);
                }
            } else {
                die("Erreur lors de la génération du QR code: le fichier n'a pas été créé.");
            }
        } else {
            die("Erreur lors de l'ajout du composant: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Erreur lors de la préparation de la requête d'insertion du composant: " . $conn->error);
    }
} else {
    die("Tous les champs sont requis.");
}

// Fermer la connexion à la base de données
$conn->close();
?>
