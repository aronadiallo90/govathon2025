-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 14 avr. 2025 à 17:47
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
-- Base de données : `govathon2025`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe_hash` varchar(255) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `secteur_id` int(11) DEFAULT NULL,
  `is_superadmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `mot_de_passe_hash`, `nom`, `secteur_id`, `is_superadmin`) VALUES
(2, 'aronadiallo90@gmail.com', '$2y$10$koxbU3GywEstmmVAkhi35eoutKOJKcbWpEkprKyMo3wAdV6/Vkt6G', 'Arona Admin Principal', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `criteres`
--

CREATE TABLE `criteres` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `coefficient` decimal(4,2) NOT NULL CHECK (`coefficient` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `ordre` int(11) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `etapes`
--

INSERT INTO `etapes` (`id`, `nom`, `ordre`, `date_debut`, `date_fin`) VALUES
(1, 'Soumission', 1, '2025-04-01', '2025-04-07'),
(2, 'Évaluation technique', 2, '2025-04-08', '2025-04-11'),
(3, 'Pitch final', 3, '2025-04-12', '2025-04-13'),
(4, 'Résultats', 4, '2025-04-14', '2025-04-15');

-- --------------------------------------------------------

--
-- Structure de la table `jurys`
--

CREATE TABLE `jurys` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `type` enum('Technique','Business','Externe') NOT NULL,
  `secteur_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `jurys`
--

INSERT INTO `jurys` (`id`, `nom`, `email`, `telephone`, `type`, `secteur_id`) VALUES
(1, 'Dr. Sarr', 'dr.sarr@education.sn', '221700000001', 'Technique', 1),
(2, 'Mme Diouf', 'diouf@health.sn', '221700000002', 'Externe', 2),
(3, 'M. Ba', 'mba@agriculture.sn', '221700000003', 'Business', 3),
(4, 'Mme Ndiaye', 'ndiaye@globaljury.sn', '221700000004', 'Technique', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id` int(11) NOT NULL,
  `nom_equipe` varchar(100) NOT NULL,
  `nom_projet` varchar(100) NOT NULL,
  `etablissement` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `secteur_id` int(11) DEFAULT NULL,
  `theme` varchar(150) DEFAULT NULL,
  `avancement_prototype` varchar(50) DEFAULT NULL,
  `play_video_url` text DEFAULT NULL,
  `detail` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `nom_equipe`, `nom_projet`, `etablissement`, `email`, `telephone`, `secteur_id`, `theme`, `avancement_prototype`, `play_video_url`, `detail`) VALUES
(9, 'Equipe Tes', 'ProjetTest', 'DAKAR', 'amiinataba@outlook.fr', '+221776791039', 1, 'Agriculture', '40', 'https://www.youtube.com/', 'djk,ng,nnlkhnkhn,dghhdhjjjf'),
(10, 'rrrrrrrrrrrrr', 'rrrrrrrrrrrrrrrr', 'DAKAR', 'aronadiadiallo@esp.sn', '+221776791039', 4, 'Agriculture', '47', '', 'jhjjh'),
(11, 'EduTrack', 'IA_DIANGALMA', 'ESMT', 'fall.mamadou.edu@esmt.sn', '221762955506', 1, 'Apprentissage autonome et ludique assisté par IA', '30', NULL, 'Projet d\'éducation basé sur l\'IA'),
(12, 'AgroTech', 'SmartFarm', 'UCAD', 'agrotech@gmail.com', '221778888888', 3, 'Optimisation des cultures via IoT', '60', NULL, 'Système connecté pour fermes'),
(13, 'HealthAI', 'MediBot', NULL, 'medibot@startup.sn', '221779999999', 2, 'Assistance médicale par chatbot IA', '45', NULL, 'Chatbot médical pour consultations'),
(14, 'GreenWorld', 'Recyclo', 'SUPINFO', 'recyclo@green.org', '221766666666', 5, 'Recyclage intelligent pour villes propres', '75', NULL, 'Solution de tri intelligent de déchets'),
(15, 'yyyyyyy', 'yyyyyyyyyyyyy', 'DAKAR', 'fmamad12345@gmail.com', '+221776791039', 4, 'Agriculture', '59', 'file:///C:/Users/Arona/Downloads/kaiadmin-lite-1.2.0%20(1)/kaiadmin-lite-1.2.0/tables/datatables.html', 'ce projet est un esoiu\r\nf\r\n\r\nsfggsstr\r\nsfgrtr\r\ntsyq\r\nsgty\r\nstth');

-- --------------------------------------------------------

--
-- Structure de la table `projets_etapes`
--

CREATE TABLE `projets_etapes` (
  `id` int(11) NOT NULL,
  `projet_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `est_selectionne` tinyint(1) DEFAULT 1,
  `note_finale` decimal(5,2) DEFAULT NULL,
  `classement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `secteurs`
--

CREATE TABLE `secteurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `secteurs`
--

INSERT INTO `secteurs` (`id`, `nom`) VALUES
(3, 'Agriculture'),
(1, 'Éducation'),
(4, 'Énergie'),
(6, 'Fonction Publique'),
(5, 'Inclusion financière'),
(2, 'Santé');

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `jury_id` int(11) NOT NULL,
  `projet_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `note` decimal(4,2) DEFAULT NULL CHECK (`note` >= 0 and `note` <= 10),
  `commentaire` text DEFAULT NULL,
  `date_vote` timestamp NOT NULL DEFAULT current_timestamp(),
  `critere_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `votes_criteres`
--

CREATE TABLE `votes_criteres` (
  `id` int(11) NOT NULL,
  `jury_id` int(11) NOT NULL,
  `projet_id` int(11) NOT NULL,
  `etape_id` int(11) NOT NULL,
  `critere_id` int(11) NOT NULL,
  `note` decimal(4,2) DEFAULT NULL CHECK (`note` >= 0 and `note` <= 10),
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `secteur_id` (`secteur_id`);

--
-- Index pour la table `criteres`
--
ALTER TABLE `criteres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ordre` (`ordre`);

--
-- Index pour la table `jurys`
--
ALTER TABLE `jurys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `secteur_id` (`secteur_id`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `secteur_id` (`secteur_id`);

--
-- Index pour la table `projets_etapes`
--
ALTER TABLE `projets_etapes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projet_id` (`projet_id`,`etape_id`),
  ADD KEY `etape_id` (`etape_id`);

--
-- Index pour la table `secteurs`
--
ALTER TABLE `secteurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jury_id` (`jury_id`,`projet_id`,`etape_id`),
  ADD KEY `projet_id` (`projet_id`,`etape_id`),
  ADD KEY `fk_critere` (`critere_id`);

--
-- Index pour la table `votes_criteres`
--
ALTER TABLE `votes_criteres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jury_id` (`jury_id`),
  ADD KEY `projet_id` (`projet_id`,`etape_id`),
  ADD KEY `critere_id` (`critere_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `criteres`
--
ALTER TABLE `criteres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `jurys`
--
ALTER TABLE `jurys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `projets_etapes`
--
ALTER TABLE `projets_etapes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `secteurs`
--
ALTER TABLE `secteurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `votes_criteres`
--
ALTER TABLE `votes_criteres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`secteur_id`) REFERENCES `secteurs` (`id`);

--
-- Contraintes pour la table `jurys`
--
ALTER TABLE `jurys`
  ADD CONSTRAINT `jurys_ibfk_1` FOREIGN KEY (`secteur_id`) REFERENCES `secteurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `projets_ibfk_1` FOREIGN KEY (`secteur_id`) REFERENCES `secteurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `projets_etapes`
--
ALTER TABLE `projets_etapes`
  ADD CONSTRAINT `projets_etapes_ibfk_1` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projets_etapes_ibfk_2` FOREIGN KEY (`etape_id`) REFERENCES `etapes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_critere` FOREIGN KEY (`critere_id`) REFERENCES `criteres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`jury_id`) REFERENCES `jurys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`projet_id`,`etape_id`) REFERENCES `projets_etapes` (`projet_id`, `etape_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `votes_criteres`
--
ALTER TABLE `votes_criteres`
  ADD CONSTRAINT `votes_criteres_ibfk_1` FOREIGN KEY (`jury_id`) REFERENCES `jurys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_criteres_ibfk_2` FOREIGN KEY (`projet_id`,`etape_id`) REFERENCES `projets_etapes` (`projet_id`, `etape_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_criteres_ibfk_3` FOREIGN KEY (`critere_id`) REFERENCES `criteres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
