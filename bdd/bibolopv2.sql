-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 19 fév. 2020 à 15:53
-- Version du serveur :  5.7.17
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bibolop`
--

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `idMedia` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `nomFichierMedia` text NOT NULL,
  `typeMedia` text NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`idMedia`, `idPost`, `nomFichierMedia`, `typeMedia`, `creationDate`, `modificationDate`) VALUES
(1, 1, 'raw1', '.jpg', '2020-02-18 13:01:15', '2020-02-18 13:01:15'),
(2, 1, 'redAlien1', '.png', '2020-02-18 13:01:15', '2020-02-18 13:01:15'),
(3, 1, 'Sans titre1', '.png', '2020-02-18 13:01:15', '2020-02-18 13:01:15'),
(4, 1, 'ship1', '.png', '2020-02-18 13:01:15', '2020-02-18 13:01:15'),
(5, 1, 'vaisseau1', '.png', '2020-02-18 13:01:15', '2020-02-18 13:01:15'),
(6, 2, 'paysagePluvieux2', '.jpg', '2020-02-19 12:29:47', '2020-02-19 12:29:47'),
(7, 3, 'ship3', '.png', '2020-02-19 12:29:55', '2020-02-19 12:29:55'),
(8, 3, 'vaisseau3', '.png', '2020-02-19 12:29:55', '2020-02-19 12:29:55'),
(9, 5, 'ship5', '.png', '2020-02-19 12:58:21', '2020-02-19 12:58:21'),
(10, 6, 'paysagePluvieux6', '.jpg', '2020-02-19 12:58:55', '2020-02-19 12:58:55'),
(11, 7, 'paysagePluvieux7', '.jpg', '2020-02-19 12:59:15', '2020-02-19 12:59:15'),
(12, 8, 'paysagePluvieux8', '.jpg', '2020-02-19 12:59:34', '2020-02-19 12:59:34'),
(13, 9, 'paysagePluvieux9', '.jpg', '2020-02-19 13:00:06', '2020-02-19 13:00:06'),
(14, 10, 'paysagePluvieux10', '.jpg', '2020-02-19 13:03:07', '2020-02-19 13:03:07'),
(15, 11, 'paysagePluvieux11', '.jpg', '2020-02-19 13:03:27', '2020-02-19 13:03:27'),
(16, 12, 'paysagePluvieux12', '.jpg', '2020-02-19 13:08:54', '2020-02-19 13:08:54'),
(17, 13, 'paysagePluvieux13', '.jpg', '2020-02-19 13:09:12', '2020-02-19 13:09:12'),
(18, 14, 'paysagePluvieux14', '.jpg', '2020-02-19 13:09:28', '2020-02-19 13:09:28'),
(19, 15, 'paysagePluvieux15', '.jpg', '2020-02-19 13:10:02', '2020-02-19 13:10:02'),
(20, 16, 'paysagePluvieux16', '.jpg', '2020-02-19 13:10:13', '2020-02-19 13:10:13'),
(21, 17, 'paysagePluvieux17', '.jpg', '2020-02-19 13:10:33', '2020-02-19 13:10:33'),
(22, 18, 'ship18', '.png', '2020-02-19 13:10:55', '2020-02-19 13:10:55'),
(23, 19, 'ship19', '.png', '2020-02-19 13:11:52', '2020-02-19 13:11:52'),
(24, 20, 'ship20', '.png', '2020-02-19 13:12:53', '2020-02-19 13:12:53'),
(25, 21, 'ship21', '.png', '2020-02-19 13:13:07', '2020-02-19 13:13:07'),
(26, 22, 'ship22', '.png', '2020-02-19 13:13:52', '2020-02-19 13:13:52'),
(27, 23, 'ship23', '.png', '2020-02-19 13:14:21', '2020-02-19 13:14:21'),
(28, 24, 'ship24', '.png', '2020-02-19 13:14:27', '2020-02-19 13:14:27'),
(29, 25, 'paysagePluvieux25', '.jpg', '2020-02-19 13:15:08', '2020-02-19 13:15:08'),
(30, 26, 'paysagePluvieux26', '.jpg', '2020-02-19 13:15:24', '2020-02-19 13:15:24'),
(31, 27, 'raw27', '.jpg', '2020-02-19 14:15:38', '2020-02-19 14:15:38'),
(32, 27, 'vaisseau27', '.png', '2020-02-19 14:15:39', '2020-02-19 14:15:39');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `idPost` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`idPost`, `commentaire`, `creationDate`, `modificationDate`) VALUES
(1, 'dab', '2020-02-18 13:01:15', '2020-02-18 13:01:15'),
(2, 'Test 1 ', '2020-02-19 12:29:47', '2020-02-19 12:29:47'),
(3, 'Test 2 :', '2020-02-19 12:29:55', '2020-02-19 12:29:55'),
(4, 'test', '2020-02-19 12:58:10', '2020-02-19 12:58:10'),
(5, 'test', '2020-02-19 12:58:21', '2020-02-19 12:58:21'),
(6, 'tt', '2020-02-19 12:58:55', '2020-02-19 12:58:55'),
(7, 'ba', '2020-02-19 12:59:15', '2020-02-19 12:59:15'),
(8, 'ertret', '2020-02-19 12:59:33', '2020-02-19 12:59:33'),
(9, 'ertret', '2020-02-19 13:00:06', '2020-02-19 13:00:06'),
(10, 'ereer', '2020-02-19 13:03:07', '2020-02-19 13:03:07'),
(11, 'ereer', '2020-02-19 13:03:27', '2020-02-19 13:03:27'),
(12, 'ereer', '2020-02-19 13:08:54', '2020-02-19 13:08:54'),
(13, 'ereer', '2020-02-19 13:09:12', '2020-02-19 13:09:12'),
(14, 'ereer', '2020-02-19 13:09:28', '2020-02-19 13:09:28'),
(15, 'ereer', '2020-02-19 13:10:02', '2020-02-19 13:10:02'),
(16, 'ereer', '2020-02-19 13:10:13', '2020-02-19 13:10:13'),
(17, 'ereer', '2020-02-19 13:10:33', '2020-02-19 13:10:33'),
(18, 'ere', '2020-02-19 13:10:55', '2020-02-19 13:10:55'),
(19, 'ere', '2020-02-19 13:11:52', '2020-02-19 13:11:52'),
(20, 'ere', '2020-02-19 13:12:53', '2020-02-19 13:12:53'),
(21, 'ere', '2020-02-19 13:13:07', '2020-02-19 13:13:07'),
(22, 'ere', '2020-02-19 13:13:52', '2020-02-19 13:13:52'),
(23, 'ere', '2020-02-19 13:14:21', '2020-02-19 13:14:21'),
(24, 'ere', '2020-02-19 13:14:27', '2020-02-19 13:14:27'),
(25, 'ee', '2020-02-19 13:15:08', '2020-02-19 13:15:08'),
(26, 'erterter', '2020-02-19 13:15:24', '2020-02-19 13:15:24'),
(27, 'erer', '2020-02-19 14:15:38', '2020-02-19 14:15:38');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`idMedia`),
  ADD KEY `idPost` (`idPost`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`idPost`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `idMedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `idPost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
