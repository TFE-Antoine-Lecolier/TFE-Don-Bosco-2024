<?php
// Inclure le fichier des recommandations de composants
include 'component_recommendations.php';

if (!isset($_SESSION['user_logged_in'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../accueil.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'inventaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Recherche De Composants </h2>
    <form action="/component/search_results.php" method="get">
        <label for="search_term">Recherche :</label>
        <input type="text" id="search_term" name="search_term" list="component_suggestions" required>
        <datalist id="component_suggestions">
            <?php foreach ($component_suggestions_search as $suggestion): ?>
                <option value="<?php echo htmlspecialchars($suggestion); ?>"></option>
            <?php endforeach; ?>
        </datalist>
        <button type="submit">Rechercher</button>
    </form>
</body>
</html>
