<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../accueil.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../config.php';

// Fonction pour obtenir l'icône de tri
function getSortArrow($column, $sort_by, $sort_order) {
    if ($column == $sort_by) {
        return $sort_order == 'asc' ? '▲' : '▼';
    }
    return '';
}

// Fonction pour enregistrer une action dans les logs
function logAction($conn, $user_id, $component_name, $action) {
    $current_time = date('Y-m-d H:i:s'); // Obtenir la date et l'heure actuelles
    $log_query = "INSERT INTO component_logs (user_id, component_name, date_added, action) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($log_query)) {
        $stmt->bind_param("isss", $user_id, $component_name, $current_time, $action);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Erreur lors de la préparation de la requête de journalisation: " . $conn->error);
    }
}

// Traitement de la suppression du composant
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Récupérer le nom du composant avant la suppression
    $component_query = "SELECT name FROM components WHERE id = ?";
    if ($stmt = $conn->prepare($component_query)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->bind_result($component_name);
        $stmt->fetch();
        $stmt->close();
    }

    // Requête SQL pour supprimer le composant
    $delete_query = "DELETE FROM components WHERE id = $delete_id";

    if ($conn->query($delete_query) === TRUE) {
        // Enregistrer l'action de suppression dans les logs
        logAction($conn, $_SESSION['user_id'], $component_name, 'Suppresion');

        echo "Composant supprimé avec succès.";
        // Rafraîchir la page pour afficher la liste mise à jour des composants
        header("Refresh:0; url=components_list.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du composant: " . $conn->error;
    }
}

// Traitement de la recherche et du tri
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sort_by = isset($_GET['sort_by']) ? $conn->real_escape_string($_GET['sort_by']) : 'name';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc' ? 'desc' : 'asc';
$next_order = $sort_order == 'asc' ? 'desc' : 'asc';

// Requête SQL pour sélectionner les composants avec recherche et tri
$select_query = "SELECT * FROM components WHERE name LIKE '%$search%' OR size LIKE '%$search%' OR stock LIKE '%$search%' ORDER BY $sort_by $sort_order";
$result = $conn->query($select_query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Composants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 16px;
        }
        .table-container {
            margin: 0 auto;
            max-width: 800px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: #000;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: nowrap;
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
        .sort-link {
            text-decoration: none;
            color: #000;
        }
        .back{
            background-color: #ccc;
        }
        .back:hover {
            background-color: #b3b3b3;
        }
        .search-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .search-form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }
       
.search-form input[type="text"] {
            margin-right: 10px;
            padding: 8px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 70%;
        }
        .search-form button[type="submit"] {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-form button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .return-button {
            background-color: #ccc;
            color: #fff;
            border-radius: 4px;
        }
        .return-button:hover {
            background-color: #b3b3b3;
        }
        .qr-code-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        @media (max-width: 600px) {
            body {
                font-size: 14px;
            }
            .btn {
                padding: 7px 9px;
                font-size: 12px;
            }
            th, td {
                padding: 6px;
            }
            .search-container {
                flex-direction: column;
            }
            .search-form input[type="text"] {
                margin-right: 0;
                margin-bottom: 10px;
                width: 100%;
            }
            .search-form button[type="submit"] {
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
    <script>
        function printQRCode(qrCodeUrl) {
            var printWindow = window.open(qrCodeUrl, '_blank');
            printWindow.print();
        }
    </script>
</head>
<body>
<header>
    <?php include '../Menu navigation.php'; ?>
</header>
<div class="table-container">
    <h2>Liste des Composants</h2>
    <div class="search-container">
        <div class="search-form">
            <form method="GET" action="components_list.php">
                <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Rechercher</button>
                <?php if (!empty($search)) : ?>
                <a href="components_list.php" style="margin-left: 10px;">❌</a>
                <?php endif; ?>
            </form>
        </div>
        <a href="../accueil.php" class="btn return-button">Retour</a>
    </div>
    <table>
        <tr>
            <th><a href="?sort_by=name&sort_order=<?php echo $next_order; ?>&search=<?php echo htmlspecialchars($search); ?>" class="sort-link">Nom <?php echo getSortArrow('name', $sort_by, $sort_order); ?></a></th>
            <th><a href="?sort_by=size&sort_order=<?php echo $next_order; ?>&search=<?php echo htmlspecialchars($search); ?>" class="sort-link">Taille <?php echo getSortArrow('size', $sort_by, $sort_order); ?></a></th>
            <th><a href="?sort_by=stock&sort_order=<?php echo $next_order; ?>&search=<?php echo htmlspecialchars($search); ?>" class="sort-link">Stock <?php echo getSortArrow('stock', $sort_by, $sort_order); ?></a></th>
            <th>QR Code + Actions </th>
        </tr>

        <?php
        // Afficher chaque composant avec des options de suppression et de modification
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['size']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";

            // Utiliser le chemin qrcodes/{id}.png
            $qr_code_url = '../qrcodes/' . $row['id'] . '.png';

            // Afficher le QR code avec un lien cliquable et un bouton d'impression
            echo "<td class='qr-code-container'>
                    <a href='" . htmlspecialchars($qr_code_url) . "' target='_blank'><img src='" . htmlspecialchars($qr_code_url) . "' alt='QR Code' width='50' height='50'></a>
                    <button onclick=\"printQRCode('" . htmlspecialchars($qr_code_url) . "')\">Imprimer</button>
                  </td>";

            echo "<td class='action-buttons'>";

            // Vérifier le rôle de l'utilisateur pour afficher les boutons en conséquence
            if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'ecriture') {
                // Afficher les boutons d'augmentation et de diminution du stock, et les boutons de modification et de suppression pour l'administrateur
                echo "<form action='update_stock.php' method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <input type='hidden' name='action' value='increase'>
                            <button type='submit' class='btn'>+</button>
                        </form>";
                echo "<form action='update_stock.php' method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <input type='hidden' name='action' value='decrease'>
                            <button type='submit' class='btn'>-</button>
                        </form>";
                echo "<a href='edit_component.php?id=" . $row['id'] . "' class='btn'>Modifier</a> ";
                echo "<a href='components_list.php?delete_id=" . $row['id'] . "' class='btn btn-danger' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer ce composant ?');\">Supprimer</a>";
            } 

            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
