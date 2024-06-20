<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barre de Navigation</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            padding-top: 70px; /* Ajustez cette valeur en fonction de la hauteur de votre barre de navigation */
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #333;
            width: 100%;
            box-sizing: border-box;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000; /* Assurez-vous que le header est au-dessus des autres éléments */
        }

        .logo {
            display: flex;
            align-items: center;
            position: relative;
            margin-right: 20px;
        }

        .logo img {
            height: 50px;
            width: auto;
            margin-left: 20px; /* Alignement à droite, donc marge à gauche */
            cursor: pointer;
        }

        .nav-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-links li {
            margin: 0;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            padding: 0.5rem 1rem;
            transition: background-color 0.3s ease;
            font-size: 18px;
        }

        .nav-links a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 999;
            padding: 10px;
            white-space: nowrap;
        }

        .dropdown-menu p {
            margin: 0;
            margin-bottom: 10px;
            color: #333; /* Couleur du texte en noir */
        }

        .dropdown-menu span#username {
            color: #333; /* Couleur du texte en noir */
        }

        .dropdown-menu a {
            display: block;
            color: #333;
            padding: 10px;
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            background-color: #ddd;
        }

        .nav-links a.changer-utilisateur {
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-size: 18px;
        }

        .nav-links a.changer-utilisateur:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Réduire la taille de la police sur les petits écrans */
        @media (max-width: 768px) {
            .nav-links a {
                font-size: 14px;
                padding: 0.4rem 0.8rem;
            }

            .dropdown-menu {
                padding: 8px;
            }

            .dropdown-menu a {
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .nav-links a {
                font-size: 11px;
                padding: 0.3rem 0.6rem;
            }

            .dropdown-menu {
                padding: 6px;
            }

            .dropdown-menu a {
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-wrapper" id="navWrapper">
            <nav>
                <ul class="nav-links">
                    <li><a href="../accueil.php">Accueil</a></li>
                    <li><a href="../users/login_admin.php">Gestion users</a></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') : ?>
                    <li><a href="../component/view_logs.php">Logs</a></li>
                    <?php endif; ?>
                    <li><a href="../login/logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </div>
        <div class="logo">
            <img src="../user.jpeg" alt="Logo" onclick="toggleMenu()">
            <div class="dropdown-menu" id="dropdownMenu">
                <p>Connecté en tant que <span id="username"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?></span></p>
                <a href="../index.php" class="changer-utilisateur">Changer d'utilisateur</a>
            </div>
        </div>
    </header>

    <script>
        function toggleMenu() {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
