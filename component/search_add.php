<?php
// Vérifiez si une session existe déjà avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez si l'utilisateur est connecté. Si non, redirigez vers login.php
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../index.php');
    exit();
}

// Récupérez le rôle de l'utilisateur
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

// Vérifiez si l'utilisateur a le rôle 'admin' ou 'write'
if ($user_role != 'admin' && $user_role != 'ecriture') {
    echo "Accès refusé. Vous n'avez pas l'autorisation d'ajouter des composants.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout D'un Composant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        button {
            padding: 5px 30px;
            font-size: 18px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #000000;
        }
        button:active {
            background-color: #FFC0CB;
        }
    </style>
</head>
<body>
    <h2>Ajout D'un Composant</h2>
    <form action="/component/add_component.php" method="get">
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
