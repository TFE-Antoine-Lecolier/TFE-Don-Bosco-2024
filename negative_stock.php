<?php
// Inclure le fichier de connexion
include './config.php';

// Requête pour obtenir les composants avec un stock négatif
$sql = "SELECT * FROM components WHERE stock < 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composants en stock négatif</title>
    <style>
        #componentList {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Tous Le Stock Négatif</h2>
    <button id="toggleButton">Voir la liste</button>
    <div id="componentList">
        <?php if ($result->num_rows > 0): ?>
            <table border="1">
                <tr>
                    <th>Nom</th>
                    <th>Taille</th>
                    <th>Stock</th>
                    <th>QR Code</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['size']); ?></td>
                        <td><?php echo htmlspecialchars($row['stock']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['qr_code_path']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($row['qr_code_path']); ?>" alt="QR Code" width="50" height="50">
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Aucun composant en stock négatif.</p>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById("toggleButton").addEventListener("click", function() {
            var componentList = document.getElementById("componentList");
            if (componentList.style.display === "none") {
                componentList.style.display = "block";
                this.textContent = "Cacher la liste";
            } else {
                componentList.style.display = "none";
                this.textContent = "Voir la liste";
            }
        });
    </script>

    <?php
    // Fermer la connexion à la base de données
    if ($conn) {
        $conn->close();
    }
    ?>
</body>
</html>
