<?php
// Tableau des recommandations de noms de composants prédéfinis
$component_suggestions_add = [
    "Résistance Ω",
    "Résistance kΩ",
    "Condensateur 10μF",
    "Transistor NPN",
    "Diode Zener V",
    "Microcontrôleur ATmega328",
    "Circuit intégré NE555",
    "Régulateur de tension LM7805",
    "LED rouge mm",
    "Capteur de température LM35",
    "Potentiomètre Ω",
    "Potentiomètre kΩ"
];

// Inclure le fichier de connexion
include 'config.php';

// Initialiser le tableau pour les suggestions de recherche
$component_suggestions_search = [
];

// Récupérer les noms des composants de la base de données
$sql = "SELECT DISTINCT name FROM components";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $component_suggestions_search[] = $row['name'];
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
