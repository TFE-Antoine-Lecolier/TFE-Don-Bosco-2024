<?php
$password1 = 'azerty123'; // Mot de passe en clair pour le premier utilisateur
$hashed_password1 = password_hash($password1, PASSWORD_BCRYPT);

$password2 = 'anotherPassword'; // Mot de passe en clair pour un autre utilisateur
$hashed_password2 = password_hash($password2, PASSWORD_BCRYPT);

echo "Mot de passe haché pour $password1 : $hashed_password1\n";
echo "Mot de passe haché pour $password2 : $hashed_password2\n";
?>