<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        /* Styles communs */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: background 0.3s;
        }

        .login-container {
            position: relative;
            width: 400px;
            padding: 40px;
            box-sizing: border-box;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            transition: background 0.3s, color 0.3s;
            background: rgba(255, 255, 255, 0.9);
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 30px;
            background: linear-gradient(45deg, #ff8c00, #ffd700);
            z-index: -1;
            filter: blur(20px);
        }

        .login-container h2 {
            margin: 0 0 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1em;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5em;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-bottom: 1px solid;
            background: transparent;
            outline: none;
            transition: border-bottom 0.3s;
        }

        .form-group input:focus {
            border-bottom: 1px solid;
        }

        .toggle-password-container {
            display: flex;
            align-items: center;
            margin-bottom: 1em;
            white-space: nowrap;
        }

        .toggle-password-container label {
            margin-right: 0.5em;
        }

        .toggle-password-container input {
            margin-right: 5px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative;
            overflow: hidden;
        }

        button[type="submit"]::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            opacity: 0;
            transition: opacity 0.3s;
        }

        button[type="submit"]:hover::after {
            opacity: 1;
        }

        /* Mode sombre */
        body.dark-mode {
            background: linear-gradient(#141e30, #243b55);
        }

        .dark-mode .login-container {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
        }

        .dark-mode .form-group input {
            color: #fff;
            border-bottom-color: #fff;
        }

        .dark-mode .form-group input:focus {
            border-bottom-color: #03e9f4;
        }

        .dark-mode button[type="submit"] {
            background-color: transparent;
            color: #fff;
        }

        /* Mode lumineux */
        body.light-mode {
            background: linear-gradient(#FFD700, #FF8C00);
        }

        .light-mode .login-container {
            background: rgba(255, 255, 255, 0.9);
            color: #000;
        }

        .light-mode .form-group input {
            color: #000;
            border-bottom-color: #000;
        }

        .light-mode .form-group input:focus {
            border-bottom-color: #FF4500;
        }

        .light-mode button[type="submit"] {
            background-color: transparent;
            color: #000;
        }

        .theme-toggle-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .theme-toggle-container svg {
            width: 24px;
            height: 24px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="theme-toggle-container" id="themeToggleContainer">
        <span id="themeToggleText">Mode lumineux</span>
        <svg id="themeToggleIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm6.364 1.636a1 1 0 011.415 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707zm-12.728 0a1 1 0 10-1.415 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zM12 5a7 7 0 100 14 7 7 0 000-14zm-1 7a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zm-4 5a1 1 0 011-1h1a1 1 0 110 2H8a1 1 0 01-1-1zm9-1a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm3.95 2.536a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zm-12.728 0a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707z" />
        </svg>
    </div>
    <div class="login-container">
        <h2>Connexion</h2>
        <form action="./login/process_login.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group toggle-password-container">
                <label for="togglePassword">Afficher le mot de passe</label>
                <input type="checkbox" id="togglePassword">
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const themeToggleContainer = document.getElementById('themeToggleContainer');
        const themeToggleText = document.getElementById('themeToggleText');
        const themeToggleIcon = document.getElementById('themeToggleIcon');
        const body = document.body;

        togglePassword.addEventListener('change', function() {
            password.type = this.checked ? 'text' : 'password';
        });

        function setThemeBasedOnTime() {
            const hour = new Date().getHours();
            if (hour >= 7 && hour < 19) {
                body.classList.remove('dark-mode');
                body.classList.add('light-mode');
                themeToggleText.textContent = 'Mode sombre';
                themeToggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm6.364 1.636a1 1 0 011.415 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707zm-12.728 0a1 1 0 10-1.415 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zM12 5a7 7 0 100 14 7 7 0 000-14zm-1 7a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zm-4 5a1 1 0 011-1h1a1 1 0 110 2H8a1 1 0 01-1-1zm9-1a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm3.95 2.536a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zm-12.728 0a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707z" />
                `;
            } else {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                themeToggleText.textContent = 'Mode lumineux';
                themeToggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m6.364-.364l-.707.707m1.414 12.728l-.707-.707M21 12h-1M3 12H2m1.05-3.364l.707.707M4.222 4.222l.707-.707M12 21v-1m4.95-3.364l-.707-.707m-.707 1.414l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                `;
            }
        }

        themeToggleContainer.addEventListener('click', function() {
            if (body.classList.contains('dark-mode')) {
                body.classList.remove('dark-mode');
                body.classList.add('light-mode');
                themeToggleText.textContent = 'Mode sombre';
                themeToggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm6.364 1.636a1 1 0 011.415 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707zm-12.728 0a1 1 0 10-1.415 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zM12 5a7 7 0 100 14 7 7 0 000-14zm-1 7a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1zm-4 5a1 1 0 011-1h1a1 1 0 110 2H8a1 1 0 01-1-1zm9-1a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm3.95 2.536a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zm-12.728 0a1 1 0 00-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707z" />
                `;
            } else {
                body.classList.remove('light-mode');
                body.classList.add('dark-mode');
                themeToggleText.textContent = 'Mode lumineux';
                themeToggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m6.364-.364l-.707.707m1.414 12.728l-.707-.707M21 12h-1M3 12H2m1.05-3.364l.707.707M4.222 4.222l.707-.707M12 21v-1m4.95-3.364l-.707-.707m-.707 1.414l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                `;
            }
        });

        // Initial theme setting based on time
        setThemeBasedOnTime();
    </script>
</body>
</html>
