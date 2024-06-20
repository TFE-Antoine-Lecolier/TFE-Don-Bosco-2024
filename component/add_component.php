<?php
// Démarrer la session
session_start();

// Inclure le fichier de connexion
include '../config.php';



// Inclure le fichier des recommandations de composants
include '../component_recommendations.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un composant</title>
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
            margin-top: 120px; 
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
        .form-container input, .form-container select {
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
        .form-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }
        .back-button {
            text-decoration: none;
            background-color: #ccc;
            color: #000;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #b3b3b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        .autocomplete-suggestions {
            border: 1px solid #e4e4e4;
            max-height: 150px;
            overflow-y: auto;
            background-color: white;
            position: absolute;
            z-index: 9999;
            width: 100%;
        }

        .autocomplete-suggestion {
            padding: 10px;
            cursor: pointer;
        }

        .autocomplete-suggestion:hover {
            background-color: #e4e4e4;
        }
    </style>
</head>
<body>
    <header>
        <?php include '../Menu navigation.php'; ?>
    </header>
    <div class="form-container">
        <div class="form-header">
            <a href="../accueil.php" class="back-button">Retour</a>
        </div>
        <h2>Ajouter un composant</h2>
        <?php if (!empty($role_err)): ?>
            <div class="error-message"><?php echo htmlspecialchars($role_err); ?></div>
        <?php endif; ?>
        <?php if (empty($role_err)): ?>
            <form action="process_add_component.php" method="post">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" list="suggestions" required>
                <datalist id="suggestions">
                    <?php foreach ($component_suggestions_add as $suggestion): ?>
                        <option value="<?php echo htmlspecialchars($suggestion); ?>">
                    <?php endforeach; ?>
                </datalist>
                <label for="size">Taille :</label>
                <input type="text" id="size" name="size" required>
                <label for="stock">Stock :</label>
                <input type="number" id="stock" name="stock" required>
                <button type="submit">Ajouter</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const suggestions = <?php echo json_encode($component_suggestions_add); ?>;
            const input = document.getElementById('name');
            const datalist = document.getElementById('suggestions');

            input.addEventListener('input', function() {
                const value = input.value.toLowerCase();
                datalist.innerHTML = '';
                if (value.length > 0) {
                    const filtered = suggestions.filter(suggestion => suggestion.toLowerCase().includes(value));
                    filtered.forEach(suggestion => {
                        const option = document.createElement('option');
                        option.value = suggestion;
                        datalist.appendChild(option);
                    });
                }
            });
        });
    </script>
</body>
</html>
