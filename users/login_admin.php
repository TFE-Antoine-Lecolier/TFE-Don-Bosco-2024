<?php
session_start();

// Vérifiez si l'utilisateur est déjà authentifié en tant qu'administrateur
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    // Rediriger vers la page de gestion des utilisateurs
    header("Location: user_management.php");
    exit;
}

// Vérifiez si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    
    if ($password === "adminazert") {
        // Mot de passe administrateur correct, définir le rôle d'administrateur
        $_SESSION['user_role'] = 'admin';
        // Rediriger vers la page de gestion des utilisateurs
        header("Location: user_management.php");
        exit;
    } elseif ($password === "tempaccess") {
        // Mot de passe temporaire correct, définir une session temporaire
        $_SESSION['temp_access'] = true;
        // Rediriger vers la page de gestion des utilisateurs
        header("Location: user_management.php");
        exit;
    } else {
        // Le mot de passe est incorrect, afficher un message d'erreur
        $error_message = "Mot de passe incorrect. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            padding: 0 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-bottom: 10px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .error-message {
            color: #ff0000;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion Admin</h2>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Se connecter</button>
                <button type="button" class="btn-back" onclick="window.history.back();">Retour</button>
            </div>
        </form>
    </div>
</body>
</html>
