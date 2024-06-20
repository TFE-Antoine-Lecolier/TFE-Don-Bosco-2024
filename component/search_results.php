<?php
// Inclure le fichier de connexion
include '../config.php';
session_start(); // Démarrer la session pour accéder aux informations de l'utilisateur connecté

// Vérifiez si l'utilisateur est connecté. Si non, redirigez vers login.php
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../index.php');
    exit();
}

// Récupérez le rôle de l'utilisateur
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 16px;
        }
        .results-container {
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
            padding: 8px;
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
            padding: 12px 20px;
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
        .back {
            background-color: #ccc;
        }
        .back:hover {
            background-color: #b3b3b3;
        }
        .btn-bottom {
            margin-top: 20px; /* Ajustez cette valeur selon vos besoins */
        }
        @media (max-width: 600px) {
            body {
                font-size: 14px;
            }
            .btn {
                padding: 8px 16px;
                font-size: 12px;
            }
            th, td {
                padding: 6px;
            }
        }
    </style>
</head>
<body>
<header>
    <?php include '../Menu navigation.php'; ?>
</header>
<div class="results-container">
    <a href="components_list.php" class="btn back btn-bottom">Retour à la liste principale</a>
    <a href="../accueil.php" class="btn back">Retour</a>
    <?php
    if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
        $keyword = $conn->real_escape_string($_GET['search_term']);

        $sort_field = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'name';
        $sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc' ? 'desc' : 'asc';

        $sql = "SELECT * FROM components WHERE name LIKE '%$keyword%' OR size LIKE '%$keyword%' OR stock LIKE '%$keyword%' ORDER BY $sort_field $sort_order";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Résultats de la recherche pour '" . htmlspecialchars($keyword) . "'</h2>";
            echo "<table>";
            echo "<tr>
                    <th><a class='sort-link' href='?search_term=" . urlencode($keyword) . "&sort_field=name&sort_order=" . ($sort_field == 'name' && $sort_order == 'asc' ? 'desc' : 'asc') . "'>Nom " . ($sort_field == 'name' ? ($sort_order == 'asc' ? '▲' : '▼') : '') . "</a></th>
                    <th><a class='sort-link' href='?search_term=" . urlencode($keyword) . "&sort_field=size&sort_order=" . ($sort_field == 'size' && $sort_order == 'asc' ? 'desc' : 'asc') . "'>Taille " . ($sort_field == 'size' ? ($sort_order == 'asc' ? '▲' : '▼') : '') . "</a></th>
                    <th><a class='sort-link' href='?search_term=" . urlencode($keyword) . "&sort_field=stock&sort_order=" . ($sort_field == 'stock' && $sort_order == 'asc' ? 'desc' : 'asc') . "'>Stock " . ($sort_field == 'stock' ? ($sort_order == 'asc' ? '▲' : '▼') : '') . "</a></th>
                    <th>QR Code</th>
                    <th>Actions</th>
                  </tr>";
            while ($row = $result->fetch_assoc()) {
                $qr_code_url = '../qrcodes/' . $row['id'] . '.png';
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['size']) . "</td>";
                echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($qr_code_url) . "' target='_blank'><img src='" . htmlspecialchars($qr_code_url) . "' alt='QR Code' width='50' height='50'></a></td>";
                echo "<td class='action-buttons'>";
                if ($user_role == 'admin' || $user_role == 'ecriture') {
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
                    echo "<form action='edit_component.php' method='get' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                            <button type='submit' class='btn'>Modifier</button>
                          </form>";
                    echo "<form action='components_list.php' method='get' style='display:inline;' onsubmit=\"return confirm('Êtes-vous sûr de vouloir supprimer ce composant ?');\">
                            <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                            <button type='submit' class='btn btn-danger'>Supprimer</button>
                          </form>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Aucun résultat trouvé pour '" . htmlspecialchars($keyword) . "'</p>";
        }
    } else {
        echo "<p>Veuillez entrer un terme de recherche.</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
