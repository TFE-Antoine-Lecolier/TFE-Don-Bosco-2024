-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 16 juin 2024 à 12:33
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_stock`
--

-- --------------------------------------------------------

--
-- Structure de la table `components`
--

CREATE TABLE `components` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `qr_code_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `components`
--

INSERT INTO `components` (`id`, `name`, `size`, `stock`, `qr_code_path`) VALUES
(11, 'Résistance kΩ', '1', 12, '../qrcodes/11.png'),
(12, 'Résistance Ω', 'Azer', 1, '../qrcodes/12.png'),
(13, 'Résistance Ω', '32', 1, '../qrcodes/13.png'),
(14, 'Résistance Ω', '32', -1, '../qrcodes/14.png'),
(15, 'Résistance Ω', '32', -1, '../qrcodes/15.png'),
(16, 'Résistance Ω', '32', 0, '../qrcodes/16.png');

-- --------------------------------------------------------

--
-- Structure de la table `component_logs`
--

CREATE TABLE `component_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `component_name` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'Ajout de composant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `component_logs`
--

INSERT INTO `component_logs` (`id`, `user_id`, `component_name`, `date_added`, `action`) VALUES
(25, 1, 'Résistance kΩ', '2024-06-15 22:55:49', 'Ajout Du Stock'),
(26, 1, 'test', '2024-06-15 23:25:07', 'Ajout du composant'),
(27, 1, 'Résistance kΩ', '2024-06-15 23:25:24', 'Ajout Du Stock'),
(28, 1, 'test', '2024-06-15 23:25:28', 'Suppresion'),
(29, 1, 'Résistance kΩ', '2024-06-15 23:31:11', 'Diminution Du Stock'),
(30, 1, 'Résistance Ω', '2024-06-15 23:33:40', 'Diminution Du Stock'),
(31, 1, 'Résistance Ω', '2024-06-15 23:33:41', 'Diminution Du Stock'),
(32, 1, 'Résistance Ω', '2024-06-15 23:33:43', 'Diminution Du Stock'),
(33, 1, 'Résistance Ω', '2024-06-15 23:33:45', 'Diminution Du Stock'),
(34, 1, 'Résistance Ω', '2024-06-15 23:33:46', 'Diminution Du Stock');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `modified_at`) VALUES
(1, 'guest', 'admin@example.com', '$2y$10$unH1nN4/U7ciGH660B213OhCxAV4vNYlhD17v0R6T7.mBkXksFpSe', 'admin', '2024-06-10 21:03:34', '2024-06-15 18:50:42'),
(2, 'user1', 'user1@example.com', '$2y$10$fjyB8tvb9aySoTNZyV0zVOPKG9pwBJ0w7zqPgbdCEicpXJvWcINjq', 'lecture', '2024-06-10 21:03:34', '2024-06-12 17:47:47'),
(3, 'user2', 'user2@example.com', 'hashedpassword', 'lecture', '2024-06-10 21:03:34', '2024-06-10 21:14:49');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `component_logs`
--
ALTER TABLE `component_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `components`
--
ALTER TABLE `components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `component_logs`
--
ALTER TABLE `component_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `component_logs`
--
ALTER TABLE `component_logs`
  ADD CONSTRAINT `component_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
