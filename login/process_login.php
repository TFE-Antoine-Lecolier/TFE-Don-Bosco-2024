<?php
session_start();
$host = 'localhost';
$db = 'gestion_stock';
$user = 'root';
$pass = 'Qwerty123?';

// Création de la connexion
$conn = new mysqli($host, $user, $pass, $db);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, role, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $row['id']; // Ajout de l'id de l'utilisateur à la session
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['username'] = $username;
            header("Location: ../accueil.php"); // Rediriger vers accueil.php après connexion réussie
            exit(); // Terminer le script après la redirection
        } else {
            // Rediriger vers la page d'erreur de mot de passe incorrect
            header("Location: error_password.php");
            exit(); // Terminer le script après la redirection
        }
    } else {
        // Rediriger vers la page d'erreur de nom d'utilisateur incorrect
        header("Location: error_username.php");
        exit(); // Terminer le script après la redirection
    }

    $stmt->close();
} else {
    echo "Veuillez remplir tous les champs.";
}

$conn->close();
?>
