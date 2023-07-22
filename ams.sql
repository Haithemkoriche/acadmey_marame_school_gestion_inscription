-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 18 juil. 2023 à 22:19
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ams`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username_admin` varchar(50) NOT NULL,
  `password_admin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id_contact` int(6) UNSIGNED NOT NULL,
  `name_contact` varchar(50) NOT NULL,
  `email_contact` varchar(50) NOT NULL,
  `message_contact` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id_contact`, `name_contact`, `email_contact`, `message_contact`) VALUES
(2, 'Haithem Koriche', 'korichehaithem2018@gmail.com', 'ddddddd');

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) NOT NULL,
  `course_description` text NOT NULL,
  `course_image` varchar(255) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_description`, `course_image`, `instructor_id`) VALUES
(1, 'deniaa', 'tu veux obtenir diplome zen9a suiver nous ', 'upload/WhatsApp Image 2023-05-23 à 11.48.53.jpg', 1),
(2, 'zen9a', 'Id nulla mollitia vo', 'upload/WhatsApp Image 2023-05-23 à 11.48.27.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `demande_ins`
--

CREATE TABLE `demande_ins` (
  `id_ins` int(6) UNSIGNED NOT NULL,
  `nom_ins` varchar(50) NOT NULL,
  `prenom_ins` varchar(50) NOT NULL,
  `date_naissance_ins` date NOT NULL,
  `numero_telephone_ins` varchar(20) NOT NULL,
  `email_ins` varchar(50) NOT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `demande_ins`
--

INSERT INTO `demande_ins` (`id_ins`, `nom_ins`, `prenom_ins`, `date_naissance_ins`, `numero_telephone_ins`, `email_ins`, `course_id`) VALUES
(12, 'Rosalyn Donaldson', 'Eve', '2004-05-17', '+1 (194) 278-9569', 'fygusopiw@mailinator.com', 1);

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id_etudiant` int(11) NOT NULL,
  `nom_etu` varchar(50) NOT NULL,
  `prenom_etu` varchar(50) NOT NULL,
  `date_naissance_etu` date NOT NULL,
  `numero_telephone_etu` varchar(20) NOT NULL,
  `email_etu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `nom_etu`, `prenom_etu`, `date_naissance_etu`, `numero_telephone_etu`, `email_etu`) VALUES
(7, 'Malachi Sloan', 'Connor', '1970-10-01', '+1 (367) 944-3735', 'qutosiris@mailinator.com'),
(8, 'Daniel Valentine', 'Macy', '1980-02-17', '+1 (832) 721-4733', 'mahyqydo@mailinator.com'),
(9, 'Adara Christian', 'Quyn', '1973-10-14', '+1 (568) 518-2863', 'xuhone@mailinator.com'),
(10, 'Adara Christian', 'Quyn', '1973-10-14', '+1 (568) 518-2863', 'xuhone@mailinator.com'),
(11, 'Sloane Gilbert', 'Noble', '1990-05-24', '+1 (882) 624-9234', 'gaconoboq@mailinator.com'),
(12, 'Sloane Gilbert', 'Noble', '1990-05-24', '+1 (882) 624-9234', 'gaconoboq@mailinator.com'),
(13, 'Sloane Gilbert', 'Noble', '1990-05-24', '+1 (882) 624-9234', 'gaconoboq@mailinator.com'),
(14, 'Sloane Gilbert', 'Noble', '1990-05-24', '+1 (882) 624-9234', 'gaconoboq@mailinator.com'),
(15, 'Raphael Becker', 'Ifeoma', '2010-08-01', '+1 (136) 572-6731', 'muqoripi@mailinator.com'),
(16, 'Raphael Becker', 'Ifeoma', '2010-08-01', '+1 (136) 572-6731', 'muqoripi@mailinator.com'),
(17, 'Raphael Becker', 'Ifeoma', '2010-08-01', '+1 (136) 572-6731', 'muqoripi@mailinator.com'),
(18, 'Raphael Becker  Ifeoma', 'Ifeoma', '2010-08-01', '+1 (136) 572-67319', 'muqoripi@mailinator.com'),
(19, 'Graham Newman', 'Cathleen', '2010-10-04', '+1 (521) 382-1284', 'liryxys@mailinator.com'),
(20, 'Graham Newman', 'Cathleen', '2010-10-04', '+1 (521) 382-1284', 'liryxys@mailinator.com'),
(21, 'Graham Newman', 'Cathleen', '2010-10-04', '+1 (521) 382-1284', 'liryxys@mailinator.com'),
(23, '', '', '0000-00-00', '', ''),
(27, 'Gillian Brooks', '', '0000-00-00', '+1 (176) 415-2012', 'hyhugebo@mailinator.com'),
(28, 'Gillian Brooks', '', '0000-00-00', '+1 (176) 415-2012', 'hyhugebo@mailinator.com'),
(29, 'Lisandra Alvarez  k', '', '0000-00-00', '+1 (696) 595-8216', 'byjyducu@mailinator.com');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant_speciality`
--

CREATE TABLE `etudiant_speciality` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) DEFAULT NULL,
  `id_instructor` int(11) DEFAULT NULL,
  `id_course` int(11) DEFAULT NULL,
  `date_debut` date DEFAULT current_timestamp(),
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `etudiant_speciality`
--

INSERT INTO `etudiant_speciality` (`id`, `id_etudiant`, `id_instructor`, `id_course`, `date_debut`, `date_fin`) VALUES
(9, 18, 1, 1, '2023-07-07', '0000-00-00'),
(16, 29, 1, 1, '2005-01-21', '0000-00-00');

-- --------------------------------------------------------

--
-- Structure de la table `instructors`
--

CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL,
  `instructor_name` varchar(50) NOT NULL,
  `instructor_email` varchar(255) NOT NULL,
  `instructor_phone` varchar(20) NOT NULL,
  `instructor_image` varchar(255) DEFAULT NULL,
  `instructor_description` text DEFAULT NULL,
  `instructor_specialty` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `instructor_name`, `instructor_email`, `instructor_phone`, `instructor_image`, `instructor_description`, `instructor_specialty`) VALUES
(1, 'Haithem Koriche', 'korichehaithem2018@gmail.com', '0555725285', 'upload/Sans titre-1.png', 'je suis kriche haithem je suis un devoppeur et je maitraise plusieur langue de devlopement ', 'denia');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id_contact`);

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Index pour la table `demande_ins`
--
ALTER TABLE `demande_ins`
  ADD PRIMARY KEY (`id_ins`),
  ADD KEY `demande_ins_ibfk_1` (`course_id`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id_etudiant`),
  ADD KEY `id_etudiant` (`id_etudiant`);

--
-- Index pour la table `etudiant_speciality`
--
ALTER TABLE `etudiant_speciality`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_instructor` (`id_instructor`),
  ADD KEY `id_course` (`id_course`);

--
-- Index pour la table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`instructor_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id_contact` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `demande_ins`
--
ALTER TABLE `demande_ins`
  MODIFY `id_ins` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id_etudiant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `etudiant_speciality`
--
ALTER TABLE `etudiant_speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `demande_ins`
--
ALTER TABLE `demande_ins`
  ADD CONSTRAINT `demande_ins_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `etudiant_speciality`
--
ALTER TABLE `etudiant_speciality`
  ADD CONSTRAINT `etudiant_speciality_ibfk_1` FOREIGN KEY (`id_course`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etudiant_speciality_ibfk_2` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id_etudiant`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `etudiant_speciality_ibfk_3` FOREIGN KEY (`id_instructor`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
