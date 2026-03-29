-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 29 mars 2026 à 22:36
-- Version du serveur : 8.0.44
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `club_lecture`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteurs`
--

CREATE TABLE `auteurs` (
  `id` int NOT NULL,
  `nom` varchar(150) NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `nationalite` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `livre_id` int NOT NULL,
  `note` tinyint NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `contenu` text,
  `spoiler` tinyint(1) DEFAULT '0',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Structure de la table `details_session`
--

CREATE TABLE `details_session` (
  `id` int NOT NULL,
  `session_id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `date_rejoins` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('Moderateur','Membre') DEFAULT 'Membre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int NOT NULL,
  `livre_id` int NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin` varchar(255) NOT NULL,
  `type_mime` varchar(100) NOT NULL,
  `taille` int NOT NULL,
  `uploade_par` int NOT NULL,
  `uploade_le` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lectures`
--

CREATE TABLE `lectures` (
  `id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `livre_id` int NOT NULL,
  `session_id` int DEFAULT NULL,
  `date_debut` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_fin` datetime DEFAULT NULL,
  `page_actuelle` int DEFAULT '0',
  `pourcentage` decimal(5,2) DEFAULT '0.00',
  `statut` enum('À lire','En cours','Terminée','Abandonnée') DEFAULT 'En cours'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déclencheurs `lectures`
--
DELIMITER $$
CREATE TRIGGER `maj_pourcentage` BEFORE UPDATE ON `lectures` FOR EACH ROW SET NEW.pourcentage = ROUND((NEW.page_actuelle /
    (SELECT nombre_pages FROM livres WHERE id = NEW.livre_id)) * 100, 2)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` int NOT NULL,
  `titre` varchar(200) NOT NULL,
  `auteur_id` int NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `nombre_pages` smallint UNSIGNED NOT NULL,
  `description` text,
  `couverture_url` varchar(255) DEFAULT NULL,
  `date_publication` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions_lecture`
--

CREATE TABLE `sessions_lecture` (
  `id` int NOT NULL,
  `titre` varchar(200) NOT NULL,
  `livre_id` int NOT NULL,
  `moderateur_id` int NOT NULL,
  `type` enum('lecture','discussion') DEFAULT NULL,
  `date_debut` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_fin` datetime DEFAULT NULL,
  `statut` enum('Planifiée','En cours','Terminée') DEFAULT 'Planifiée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `admin` enum('admin','moderateur','membre') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'membre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `date_inscription`, `admin`) VALUES
(1, 'test', 'test@test.fr', '$2y$10$ik6yPbX6xJOA91lLN6pyRucRkG6GKMvVxE3Qu8YiVU4XmIUdHdcw2', '2026-03-29 13:22:47', 'membre');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `auteurs`
--
ALTER TABLE `auteurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`livre_id`),
  ADD KEY `livre_id` (`livre_id`);

--
-- Index pour la table `details_session`
--
ALTER TABLE `details_session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`,`utilisateur_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `livre_id` (`livre_id`),
  ADD KEY `uploaded_by` (`uploade_par`);

--
-- Index pour la table `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateur_id` (`utilisateur_id`,`livre_id`,`session_id`),
  ADD KEY `livre_id` (`livre_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `auteur_id` (`auteur_id`);

--
-- Index pour la table `sessions_lecture`
--
ALTER TABLE `sessions_lecture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `livre_id` (`livre_id`),
  ADD KEY `moderateur_id` (`moderateur_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `auteurs`
--
ALTER TABLE `auteurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `details_session`
--
ALTER TABLE `details_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sessions_lecture`
--
ALTER TABLE `sessions_lecture`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `details_session`
--
ALTER TABLE `details_session`
  ADD CONSTRAINT `details_session_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions_lecture` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `details_session_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`uploade_par`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lectures`
--
ALTER TABLE `lectures`
  ADD CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lectures_ibfk_2` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lectures_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `sessions_lecture` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `livres`
--
ALTER TABLE `livres`
  ADD CONSTRAINT `livres_ibfk_1` FOREIGN KEY (`auteur_id`) REFERENCES `auteurs` (`id`) ON DELETE RESTRICT;

--
-- Contraintes pour la table `sessions_lecture`
--
ALTER TABLE `sessions_lecture`
  ADD CONSTRAINT `sessions_lecture_ibfk_1` FOREIGN KEY (`livre_id`) REFERENCES `livres` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `sessions_lecture_ibfk_2` FOREIGN KEY (`moderateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
