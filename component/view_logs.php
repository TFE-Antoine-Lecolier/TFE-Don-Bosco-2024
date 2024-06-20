<?php
session_start();
include '../config.php';

// Vérifiez si l'utilisateur est connecté et s'il a les droits d'administrateur
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Récupérer les filtres de date, recherche et action depuis la requête GET
$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$action_filter = isset($_GET['action_filter']) ? $_GET['action_filter'] : '';

// Construire la requête SQL de base
$sql = "SELECT component_logs.id, users.username, component_logs.component_name, 
        DATE(component_logs.date_added) AS date, TIME(component_logs.date_added) AS time, 
        component_logs.action 
        FROM component_logs 
        JOIN users ON component_logs.user_id = users.id";

// Ajouter les filtres à la requête SQL
$conditions = [];
$params = [];

if (!empty($date_filter)) {
    $conditions[] = "DATE(component_logs.date_added) = ?";
    $params[] = $date_filter;
}

if (!empty($search_query)) {
    $conditions[] = "(users.username LIKE ? OR component_logs.component_name LIKE ?)";
    $params[] = '%' . $search_query . '%';
    $params[] = '%' . $search_query . '%';
}

if (!empty($action_filter)) {
    $conditions[] = "component_logs.action = ?";
    $params[] = $action_filter;
}

if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= " ORDER BY component_logs.date_added DESC";
$stmt = $conn->prepare($sql);

// Lier les paramètres à la requête préparée
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Erreur de la requête SQL : " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal des ajouts de composants</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
        .filter-form {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filter-form div {
            display: flex;
            flex-direction: column;
        }
        .filter-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .filter-form input, .filter-form button, .filter-form select {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
        }
        .filter-form button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .filter-form button:hover {
            background-color: #0056b3;
        }
        .back-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            overflow-x: auto;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #343a40;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        @media (max-width: 768px) {
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-form div {
                width: 100%;
            }
            .filter-form input, .filter-form button, .filter-form select {
                font-size: 14px;
            }
            table, th, td {
                font-size: 14px;
            }
        }
        @media (max-width: 480px) {
            .filter-form input, .filter-form button, .filter-form select {
                font-size: 12px;
                padding: 8px;
            }
            th, td {
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<header>
    <?php include '../Menu navigation.php'; ?>
</header>
    <h2>Journal des ajouts de composants</h2>
    
    <form class="filter-form" method="GET" action="">
        <div>
            <label for="date_filter">Filtrer par jour :</label>
            <input type="date" id="date_filter" name="date_filter" value="<?php echo htmlspecialchars($date_filter); ?>">
        </div>
        
        <div>
            <label for="search_query">Rechercher :</label>
            <input type="text" id="search_query" name="search_query" placeholder="Utilisateur ou composant" value="<?php echo htmlspecialchars($search_query); ?>">
        </div>
        
        <div>
            <label for="action_filter">Filtrer par action :</label>
            <select id="action_filter" name="action_filter">
                <option value="">Toutes les actions</option>
                <option value="Ajout Du Stock" <?php echo ($action_filter === 'Ajout Du Stock') ? 'selected' : ''; ?>>Ajout Du Stock</option>
                <option value="Diminution Du Stock" <?php echo ($action_filter === 'Diminution Du Stock') ? 'selected' : ''; ?>>Diminution du stock</option>
                <option value="Création du composant" <?php echo ($action_filter === 'Création du composant') ? 'selected' : ''; ?>>Création du composant</option>
                <option value="Suppresion" <?php echo ($action_filter === 'Suppresion') ? 'selected' : ''; ?>>Suppression du composant</option>
            </select>
        </div>
        
        <button type="submit">Filtrer</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Composant</th>
                <th>Action</th>
                <th>Date</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['component_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['action']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($row['date']))); ?></td>
                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <a href="../accueil.php" class="back-button">Retour à l'accueil</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
