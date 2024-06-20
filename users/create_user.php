<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_logged_in'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../index.php");
    exit;
}

// Inclure le fichier de configuration de la base de données
include '../config.php';

// Définir les variables avec des valeurs par défaut
$username = $password = $confirm_password = $email = $role = "";
$username_err = $password_err = $confirm_password_err = $email_err = $role_err = "";

// Traitement du formulaire lorsque le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valider le nom d'utilisateur
    if (empty(trim($_POST["username"]))) {
        $username_err = "Veuillez saisir un nom d'utilisateur.";
    } else {
        // Vérifier si le nom d'utilisateur existe déjà dans la base de données
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);

            // Paramètres
            $param_username = trim($_POST["username"]);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "Ce nom d'utilisateur est déjà pris.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
            }

            // Fermer la déclaration
            $stmt->close();
        }
    }

    // Valider le mot de passe
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez saisir un mot de passe.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Le mot de passe doit comporter au moins 6 caractères.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valider la confirmation du mot de passe
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Veuillez confirmer le mot de passe.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }

    // Valider l'e-mail
    // Valider l'e-mail
if (empty(trim($_POST["email"]))) {
    $email_err = "Veuillez saisir une adresse e-mail.";
} elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL) || strpos(trim($_POST["email"]), '@') === false) {
    $email_err = "Veuillez saisir une adresse e-mail valide contenant un '@'.";
} else {
    $email = trim($_POST["email"]);
}

    // Valider le rôle
    if (empty(trim($_POST["role"]))) {
        $role_err = "Veuillez sélectionner un rôle.";
    } else {
        $role = trim($_POST["role"]);
    }

    // Vérifier s'il y a des erreurs de saisie avant d'insérer dans la base de données
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($role_err)) {

        // Requête SQL pour insérer un nouvel utilisateur dans la base de données
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $param_username, $param_password, $param_email, $param_role);

            // Paramètres
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hashage du mot de passe
            $param_email = $email;
            $param_role = $role;

            // Exécuter la déclaration préparée
            if ($stmt->execute()) {
                // Rediriger vers la page de gestion des utilisateurs après la création réussie d'un nouvel utilisateur
                header("location: user_management.php");
            } else {
                echo "Oops! Quelque chose s'est mal passé. Veuillez réessayer plus tard.";
            }

            // Fermer la déclaration
            $stmt->close();
        }
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Un Utilisateur</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-top: -150px; 
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px; /* Marge inférieure par défaut pour le titre */
            font-weight: bold;
            font-size: 1.5em;
            margin-top: 50px; /* Marge supérieure ajoutée pour déplacer le titre vers le bas */
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .help-block {
            color: red;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-default {
            background-color: #ccc;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Supprimer le soulignement */
        }

        .btn-default:hover {
            background-color: #b3b3b3;
        }

        /* Style pour le bouton "Retour" */
        .back-button {
            text-align: right;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <?php include '../Menu navigation.php'; ?>
    </header>
   
    <div class="container">
        <!-- Bouton "Retour" -->
        <div class="back-button">
            <a href="../users/user_management.php" class="btn btn-default">Retour</a>
        </div>

       <h2 style="margin-top: 50px;">Créer Un Utilisateur</h2>
        <p>Veuillez remplir ce formulaire pour créer un nouvel utilisateur.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Nom d'Utilisateur</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="form-control">
                <span class="help-block"><?php echo htmlspecialchars($username_err); ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Mot de Passe</label>
                <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>" class="form-control">
                <span class="help-block"><?php echo htmlspecialchars($password_err); ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmer le Mot de Passe</label>
                <input type="password" name="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>" class="form-control">
                <span class="help-block"><?php echo htmlspecialchars($confirm_password_err); ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Adresse E-mail</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control">
                <span class="help-block"><?php echo htmlspecialchars($email_err); ?></span>
            </div>
            <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Rôle</label>
                <select name="role" class="form-control">
                    <option value="">Sélectionner un rôle</option>
                    <option value="lecture" <?php echo ($role == 'lecture') ? 'selected' : ''; ?>>Lecture</option>
                    <option value="ecriture" <?php echo ($role == 'ecriture') ? 'selected' : ''; ?>>Écriture</option>
                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
                <span class="help-block"><?php echo htmlspecialchars($role_err); ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Créer">
                <input type="reset" class="btn btn-default" value="Réinitialiser">
            </div>
        </form>
    </div>
</body>
</html>

