<!-- create_user_process.php -->
<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $query = "INSERT INTO users (username, email, role, created_at, modified_at) VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $email, $role);

    if ($stmt->execute()) {
        echo "Utilisateur créé avec succès.";
        header("Location: user_list.php");
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
