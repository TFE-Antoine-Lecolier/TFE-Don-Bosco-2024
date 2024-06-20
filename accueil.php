<?php
session_start();

// Vérifiez si l'utilisateur est connecté. Si non, redirigez vers login.php
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page avec Navigation</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #343a40;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }

        .container {
            padding: 7rem 1rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #343a40;
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .full-width {
            width: 100%;
            margin-bottom: 1rem;
        }

        .half-width {
            width: 48%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            flex: 1;
        }

        .card h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #343a40;
        }

        .card p {
            color: #555;
            font-size: 1rem;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .half-width {
                width: 100%;
            }
        }

        @keyframes blink {
            0% {
                background-color: white;
            }
            50% {
                background-color: red;
            }
            100% {
                background-color: white;
            }
        }

        .negative-stock-card {
            animation: blink 30s infinite;
        }
    </style>
</head>

<body>
    <header>
        <?php include 'Menu navigation.php'; ?>
    </header>
    <div class="container">
        <div class="main-content">
            <div class="card full-width">
                <?php include './component/search_inventory.php'; ?>
            </div>
            <div class="card full-width negative-stock-card">
                <?php include './negative_stock.php'; ?>
            </div>
            <div class="row">
                <div class="card half-width">
                    <?php include './component/button_list.php'; ?>
                </div>
                <div class="card half-width">
                    <?php include './component/search_add.php'; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
