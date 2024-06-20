<?php
// Démarrer la session
session_start();

// Inclure le fichier de connexion
include '../config.php';

// Initialiser les variables d'erreur
$username_err = $email_err = $password_err = $confirm_password_err = $role_err = "";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Récupérer les informations actuelles de l'utilisateur
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Utilisateur non trouvé.";
        exit;
    }
} else {
    echo "ID d'utilisateur manquant.";
    exit;
}

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider le nom d'utilisateur
    if (empty(trim($_POST["username"]))) {
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Valider l'adresse e-mail
    // Valider l'e-mail
if (empty(trim($_POST["email"]))) {
    $email_err = "Veuillez saisir une adresse e-mail.";
} elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL) || strpos(trim($_POST["email"]), '@') === false) {
    $email_err = "Veuillez saisir une adresse e-mail valide contenant un '@'.";
} else {
    $email = trim($_POST["email"]);
}


    // Valider le mot de passe
    if (!empty(trim($_POST["password"]))) {
        if (strlen(trim($_POST["password"])) < 6) {
            $password_err = "Le mot de passe doit comporter au moins 6 caractères.";
        } else {
            $password = trim($_POST["password"]);
        }
    }

    // Valider la confirmation du mot de passe
    if (!empty(trim($_POST["confirm_password"]))) {
        if (empty($password_err) && (trim($_POST["password"]) != trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Le mot de passe ne correspond pas.";
        }
    }

    // Valider le rôle
    if (empty(trim($_POST["role"]))) {
        $role_err = "Veuillez sélectionner un rôle.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Vérifier s'il n'y a pas d'erreurs avant la mise à jour
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($role_err)) {
        // Préparer la requête de mise à jour
        if (empty($password)) {
            // Mettre à jour sans changer le mot de passe
            $update_query = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
            if ($stmt = $conn->prepare($update_query)) {
                // Liaison des paramètres
                $stmt->bind_param("sssi", $param_username, $param_email, $param_role, $param_id);

                // Paramètres
                $param_username = $username;
                $param_email = $email;
                $param_role = $role;
                $param_id = $id;

                // Exécution de la déclaration
                if ($stmt->execute()) {
                    // Rediriger vers la page de liste des utilisateurs après la mise à jour
                    header("Location: user_list.php");
                    exit;
                } else {
                    echo "Erreur lors de la mise à jour de l'utilisateur.";
                }

                // Fermeture de la déclaration
                $stmt->close();
            }
        } else {
            // Mettre à jour avec un nouveau mot de passe
            $update_query = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?";
            if ($stmt = $conn->prepare($update_query)) {
                // Liaison des paramètres
                $stmt->bind_param("ssssi", $param_username, $param_email, $param_password, $param_role, $param_id);

                // Paramètres
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT);
                $param_role = $role;
                $param_id = $id;

                // Exécution de la déclaration
                if ($stmt->execute()) {
                    // Rediriger vers la page de liste des utilisateurs après la mise à jour
                    header("Location: user_list.php");
                    exit;
                } else {
                    echo "Erreur lors de la mise à jour de l'utilisateur.";
                }

                // Fermeture de la déclaration
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            position: relative; /* Ajout de position relative pour positionner le bouton */
            margin-top: 35px; 
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px; /* Marge inférieure par défaut pour le titre */
            font-weight: bold;
            font-size: 1.5em;
            margin-top: 50px; /* Marge supérieure ajoutée pour déplacer le titre vers le bas */
        }
        .container form {
            display: flex;
            flex-direction: column;
            font-weight: bold;
        }
        .container label {
            margin-bottom: 5px;
        }
        .container input, .container select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .container .btn-primary {
            padding: 10px 15px; /* Agrandir le bouton */
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }
        .container .btn-primary:hover {
            background-color: #0056b3;
        }
        .container .btn-secondary {
            position: absolute; /* Position absolue pour le bouton */
            top: 10px; /* Distance du haut */
            right: 10px; /* Distance de la droite */
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            padding: 10px 20px;
        }
        .container .btn-secondary:hover {
            background-color: #b3b3b3;
        }
        .container .help-block {
            color: red;
        }
    </style>
</head>
<body>
<header>
    <?php include '../Menu navigation.php'; ?>
</header>
<div class="container">
    <a href="user_list.php" class="btn btn-secondary">Retour</a>
    <h2 style="margin-top: 50px;">Modifier Utilisateur</h2> <!-- Ajout de la marge supérieure pour déplacer le titre -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$id"; ?>" method="post">
        <div class="form-group">
            <label for="username">Nom d'Utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($row['username']); ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label for="email">Adresse E-mail</label>
            <input type="text" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <label for="role">Rôle</label>
            <select name="role" id="role" class="form-control">
                <option value="">Sélectionner un rôle</option>
                <option value="lecture" <?php echo ($row['role'] == 'lecture') ? 'selected' : ''; ?>>Lecture</option>
                <option value="ecriture" <?php echo ($row['role'] == 'ecriture') ? 'selected' : ''; ?>>Écriture</option>
                <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option> <!-- Option "Admin" ajoutée -->
            </select>
            <span class="help-block"><?php echo $role_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Mettre à jour">
        </div>
    </form>
</div>
</body>
</html>

