<?php
// Inclure le fichier de configuration de la base de données
include '../config.php';

if(isset($_POST["query"])){
    $searchUser = $_POST["query"];
    $search_query = "SELECT * FROM users WHERE username LIKE '%$searchUser%' OR email LIKE '%$searchUser%'";
    $result = $conn->query($search_query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['username']}, {$row['email']}</li>";
        }
    } else {
        echo "<li>Aucun utilisateur trouvé.</li>";
    }
}
?>
