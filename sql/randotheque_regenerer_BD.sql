-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 19 mai 2022 à 08:18
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `randotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `ami`
--

DROP TABLE IF EXISTS `ami`;
CREATE TABLE IF NOT EXISTS `ami` (
  `Id_Utilisateur_être` int(11) NOT NULL,
  `Id_Utilisateur_avoir` int(11) NOT NULL,
  PRIMARY KEY (`Id_Utilisateur_être`,`Id_Utilisateur_avoir`),
  KEY `Id_Utilisateur_avoir` (`Id_Utilisateur_avoir`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `caracteriser`
--

DROP TABLE IF EXISTS `caracteriser`;
CREATE TABLE IF NOT EXISTS `caracteriser` (
  `Id_Fichier_GPX` int(11) NOT NULL,
  `Id_Mot_clefs` int(11) NOT NULL,
  PRIMARY KEY (`Id_Fichier_GPX`,`Id_Mot_clefs`),
  KEY `Id_Mot_clefs` (`Id_Mot_clefs`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `conversation`
--

DROP TABLE IF EXISTS `conversation`;
CREATE TABLE IF NOT EXISTS `conversation` (
  `Id_Conversation` int(11) NOT NULL AUTO_INCREMENT,
  `Libellé` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id_Conversation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fichier_gpx`
--

DROP TABLE IF EXISTS `fichier_gpx`;
CREATE TABLE IF NOT EXISTS `fichier_gpx` (
  `Id_Fichier_GPX` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) DEFAULT NULL,
  `Description` text,
  `Type_de_sport` varchar(50) DEFAULT NULL,
  `Difficulté` tinyint(4) DEFAULT NULL,
  `Localisation` varchar(50) DEFAULT NULL,
  `Id_Utilisateur` int(11) NOT NULL,
  PRIMARY KEY (`Id_Fichier_GPX`),
  KEY `Id_Utilisateur` (`Id_Utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `interagir`
--

DROP TABLE IF EXISTS `interagir`;
CREATE TABLE IF NOT EXISTS `interagir` (
  `Id_Utilisateur` int(11) NOT NULL,
  `Id_Fichier_GPX` int(11) NOT NULL,
  `Note` tinyint(4) DEFAULT NULL,
  `Commentaire` text,
  PRIMARY KEY (`Id_Utilisateur`,`Id_Fichier_GPX`),
  KEY `Id_Fichier_GPX` (`Id_Fichier_GPX`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `Id_Utilisateur` int(11) NOT NULL,
  `Date_heure` datetime NOT NULL,
  `Contenu` text,
  `Id_Conversation` int(11) NOT NULL,
  PRIMARY KEY (`Id_Utilisateur`,`Date_heure`),
  KEY `Id_Conversation` (`Id_Conversation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mot_clefs`
--

DROP TABLE IF EXISTS `mot_clefs`;
CREATE TABLE IF NOT EXISTS `mot_clefs` (
  `Id_Mot_clefs` int(11) NOT NULL AUTO_INCREMENT,
  `Libellé` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`Id_Mot_clefs`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `participer`
--

DROP TABLE IF EXISTS `participer`;
CREATE TABLE IF NOT EXISTS `participer` (
  `Id_Utilisateur` int(11) NOT NULL,
  `Id_Conversation` int(11) NOT NULL,
  PRIMARY KEY (`Id_Utilisateur`,`Id_Conversation`),
  KEY `Id_Conversation` (`Id_Conversation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `Id_Fichier_GPX` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  PRIMARY KEY (`Id_Fichier_GPX`,`Nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `Id_Utilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `Mail` varchar(50) NOT NULL,
  `Nom_d_utilisateur` varchar(50) NOT NULL,
  `Mot_de_passe` varchar(50) NOT NULL,
  `Poids` int(11) DEFAULT NULL,
  `Taille` int(11) DEFAULT NULL,
  `Sexe` char(1) DEFAULT NULL,
  `DateN` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_Utilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
