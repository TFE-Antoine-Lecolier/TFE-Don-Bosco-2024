<?php
session_start();

include '../config.php';

// Vérifiez si l'ID est fourni via GET ou POST
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : null);

if ($id !== null) {
    if (isset($_POST['password'])) {
        $password = $_POST['password'];

        // Remplacez 'aaaaaa' par le mot de passe que vous voulez utiliser
        if ($password === 'aaaaaa') {
            $_SESSION['authenticated'] = true;
            header("Location: edit_component.php?id=$id");
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    }
} else {
    // Aucun ID fourni, vérifiez le mot de passe et le nom si soumis
    if (isset($_POST['password']) && isset($_POST['name'])) {
        $password = $_POST['password'];
        $name = $_POST['name'];

        if ($password === 'aaaaaa') {
            $_SESSION['authenticated'] = true;

            // Recherchez l'ID du composant par son nom
            $sql = "SELECT id FROM components WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $stmt->bind_result($id);
            if ($stmt->fetch()) {
                header("Location: edit_component.php?id=$id");
                exit();
            } else {
                $error = "Composant non trouvé.";
            }
            $stmt->close();
        } else {
            $error = "Mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protection par mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .input-container {
            position: relative;
            width: 100%;
        }
        .input-container button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Veuillez entrer le mot de passe pour accéder à cette page</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post" action="password_protect.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="input-container">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
                <button type="button" onclick="togglePassword()">👁️</button>
            </div>
            <br>

            <?php if ($id === null): // Affichez les champs ID et Nom uniquement si aucun ID n'est fourni ?>
                <div class="input-container">
                    <label for="name">Nom du composant:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <br>
            <?php endif; ?>

            <button type="submit">Valider</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var passwordFieldType = passwordField.type;
            if (passwordFieldType === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
