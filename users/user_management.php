<?php
session_start();

// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['temp_access']) && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin')) {
    // Rediriger vers la page de connexion si l'accès n'est pas autorisé
    header("Location: index.php");
    exit;
}

// Supprimez l'accès temporaire après la redirection pour sécuriser la session
unset($_SESSION['temp_access']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Des Utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007BFF;
            padding: 10px 0;
            text-align: center;
            color: white;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 40px; 
        }
        h2 {
            text-align: center;
            color: #007BFF;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            width: 70%;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .results {
            margin-top: 20px;
        }
        .results ul {
            list-style: none;
            padding: 0;
        }
        .results li {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .results li strong {
            color: #007BFF;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <?php include '../Menu navigation.php'; ?>
    </header>
    <div class="container">
        <h2>Recherche d'Utilisateurs</h2>
        <form method="POST" action="">
            <input type="text" name="searchUser" placeholder="Rechercher un utilisateur..." required>
            <button type="submit">Rechercher</button>
        </form>
        <div class="results">
            <?php
            // Inclure le fichier de configuration de la base de données
            include '../config.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchUser'])) {
                $searchUser = $_POST['searchUser'];
                // Utilisez des requêtes préparées pour éviter les injections SQL
                $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ?");
                $searchTerm = "%$searchUser%";
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    echo "<h3>Résultats de la recherche pour '{$searchUser}':</h3>";
                    echo "<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li><strong>Nom d'utilisateur:</strong> {$row['username']}, <strong>Email:</strong> {$row['email']}, <strong>Date de création:</strong> {$row['created_at']}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Aucun utilisateur trouvé pour '{$searchUser}'.</p>";
                }
                $stmt->close();
                $conn->close();
            }
            ?>
        </div>
        <a href="user_list.php" class="back-link">Voir la liste complète des utilisateurs</a>
        <a href="temp_logout.php" class="back-link btn-back">Retour</a>
    </div>
</body>
</html>
