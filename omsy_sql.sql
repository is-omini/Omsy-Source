-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 29 oct. 2025 à 22:31
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `omsy_sql`
--

-- --------------------------------------------------------

--
-- Structure de la table `omsyreport_report`
--

CREATE TABLE `omsyreport_report` (
  `ID` int(11) NOT NULL,
  `uniqid` text NOT NULL,
  `user` text NOT NULL,
  `page` text NOT NULL,
  `tag` text NOT NULL,
  `date_` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `omsy_account`
--

CREATE TABLE `omsy_account` (
  `ID` int(11) NOT NULL,
  `uniqid` text NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` int(11) NOT NULL DEFAULT 0,
  `register_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `omsy_account_login`
--

CREATE TABLE `omsy_account_login` (
  `ID` int(11) NOT NULL,
  `token` text NOT NULL,
  `user_id` text NOT NULL,
  `reg_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `omsy_account_obj`
--

CREATE TABLE `omsy_account_obj` (
  `ID` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `name` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `omsy_object`
--

CREATE TABLE `omsy_object` (
  `ID` int(11) NOT NULL,
  `object_type` text NOT NULL,
  `object_name` text NOT NULL,
  `object_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `omsy_session`
--

CREATE TABLE `omsy_session` (
  `ID` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp(),
  `page` text NOT NULL,
  `agent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `omsy_viewpage`
--

CREATE TABLE `omsy_viewpage` (
  `ID` int(11) NOT NULL,
  `useragent` text NOT NULL,
  `userip` text NOT NULL,
  `page` text NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `omsyreport_report`
--
ALTER TABLE `omsyreport_report`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `omsy_account`
--
ALTER TABLE `omsy_account`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `omsy_account_login`
--
ALTER TABLE `omsy_account_login`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `omsy_account_obj`
--
ALTER TABLE `omsy_account_obj`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `omsy_object`
--
ALTER TABLE `omsy_object`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `omsy_session`
--
ALTER TABLE `omsy_session`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `omsy_viewpage`
--
ALTER TABLE `omsy_viewpage`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `omsyreport_report`
--
ALTER TABLE `omsyreport_report`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `omsy_account`
--
ALTER TABLE `omsy_account`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `omsy_account_login`
--
ALTER TABLE `omsy_account_login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `omsy_account_obj`
--
ALTER TABLE `omsy_account_obj`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `omsy_object`
--
ALTER TABLE `omsy_object`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `omsy_session`
--
ALTER TABLE `omsy_session`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `omsy_viewpage`
--
ALTER TABLE `omsy_viewpage`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
