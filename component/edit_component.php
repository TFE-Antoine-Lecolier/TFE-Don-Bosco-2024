<?php
session_start();

// Vérifiez si l'utilisateur est authentifié à partir de password_protect.php ou de l'index
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    // Vérifiez également si l'utilisateur est authentifié à partir de l'index
    if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
        // Si non, redirigez vers password_protect.php
        header("Location: password_protect.php?id=" . $_GET['id']);
        exit();
    }
}

// Inclure le fichier de connexion
include '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Récupérer les informations actuelles du composant
    $sql = "SELECT * FROM components WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Composant non trouvé.";
        exit;
    }
} else {
    echo "ID de composant manquant.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le composant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px; 
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container label {
            margin-bottom: 5px;
        }
        .form-container input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container .back-btn {
            text-align: right;
            margin-bottom: 10px;
        }
        .form-container .back-btn a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
        }
        .form-container .back-btn a:hover {
            background-color: #b3b3b3;
        }
    </style>
</head>
<body>
<header>
        <?php include '../Menu navigation.php'; ?>
    </header>
    <div class="form-container">
        <div class="back-btn">
            <a href="components_list.php">Retour</a>
        </div>
        <h2>Modifier le composant</h2>
        <form action="process_edit_component.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            <label for="size">Taille :</label>
            <input type="text" id="size" name="size" value="<?php echo htmlspecialchars($row['size']); ?>" required>
            <label for="stock">Stock :</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($row['stock']); ?>" required>
            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>
