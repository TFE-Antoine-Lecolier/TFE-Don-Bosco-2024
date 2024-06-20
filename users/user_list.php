<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../index.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../config.php';

// Traitement de la suppression de l'utilisateur
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sécuriser l'ID en tant qu'entier

    // Requête SQL pour supprimer l'utilisateur
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        // Utilisateur supprimé avec succès, rediriger pour rafraîchir la liste
        header("Location: user_list.php");
        exit;
    } else {
        echo "Erreur lors de la suppression de l'utilisateur: " . $stmt->error;
    }

    $stmt->close();
}

// Sélectionner tous les utilisateurs de la base de données
$select_query = "SELECT * FROM users";
$result = $conn->query($select_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
            font-size: 16px; /* Utiliser une taille de police relative */
        }
        .table-container {
            margin: 0 auto;
            max-width: 1500px;
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
            font-size: 16px; /* Réduire la taille de la police pour les petits écrans */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px; /* Réduire légèrement la taille du padding */
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-buttons {
            display: flex;
            justify-content: space-around;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 15px;
            box-shadow: 0 9px #999;
            margin: 0 5px;
        }
        .btn:hover {background-color: #3e8e41}
        .btn:active {
            background-color: #3e8e41;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {background-color: #da190b}
        .btn-danger:active {
            background-color: #da190b;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
        }

        @media (max-width: 1200px) {
            .table-container {
                max-width: 1000px;
            }
            .action-buttons {
                flex-direction: column; /* Aligner les boutons en colonne pour les petits écrans */
                align-items: center; /* Centrer les boutons */
            }
        }

        @media (max-width: 768px) {
        body {
            font-size: 14px; /* Diminuer la taille de la police pour les petits écrans */
        }
        .table-container {
            padding: 10px;
        }
        th, td {
            padding: 9px; /* Réduire encore plus le padding pour les petits écrans */
            font-size: 12px; /* Diminuer la taille de la police des champs */
        }
        .btn {
            padding: 6px 12px; /* Ajuster le padding des boutons pour les petits écrans */
            font-size: 12px; /* Diminuer la taille de la police des boutons */
        }
        .action-buttons {
            flex-direction: column; /* Aligner les boutons en colonne pour les petits écrans */
            align-items: center; /* Centrer les boutons */
            padding: 6px 12px;
        }
    }

    @media (max-width: 480px) {
        .action-buttons {
            flex-direction: column; /* Aligner les boutons en colonne pour les petits écrans */
            align-items: center; /* Centrer les boutons */
        }
        body {
            font-size: 12px; /* Diminuer la taille de la police pour les petits écrans */
        }
        th, td {
            font-size: 7px; /* Diminuer la taille de la police des champs pour les petits écrans */
        }
        .btn {
            font-size: 10px; /* Diminuer la taille de la police des boutons pour les petits écrans */
        }
    }

    .btn-action {
        width: 120px; /* Définir une largeur fixe pour les boutons "Modifier" et "Supprimer" */
    }

    </style>
</head>
<body>
    <header>
        <?php include '../Menu navigation.php'; ?>
    </header>
    <div class="table-container">
        <h2>Liste des Utilisateurs</h2>
        <a href="./user_management.php" class="btn">Retour</a> <!-- Bouton Retour ajouté -->
        <table>
            <tr>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date de création</th>
                <th>Date de modification</th>
                <th>Actions</th>
            </tr>
            <?php
            // Afficher chaque utilisateur avec des options de suppression et de modification
            while ($row = $result->fetch_assoc()) {
                $created_at = date('Y-m-d H:i:s', strtotime($row['created_at']));
                $modified_at = date('Y-m-d H:i:s', strtotime($row['modified_at']));
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td>" . htmlspecialchars($created_at) . "</td>";
                echo "<td>" . htmlspecialchars($modified_at) . "</td>";
                echo "<td class='action-buttons'>
                        <a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-action'>Modifier</a>
                        <a href='user_list.php?delete_id=" . $row['id'] . "' class='btn btn-danger btn-action' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');\">Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
        <br>
        <a href="create_user.php" class="btn">Ajouter un nouvel utilisateur</a>
    </div>
</body>
</html>
