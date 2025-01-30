-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 30 jan. 2025 à 19:51
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hville`
--

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

DROP TABLE IF EXISTS `employe`;
CREATE TABLE IF NOT EXISTS `employe` (
  `IDEMPLOYE` int(11) NOT NULL AUTO_INCREMENT,
  `FONCTION_EMPLOYE` varchar(2) NOT NULL,
  `IDSERVICE` int(11) NOT NULL,
  `NOM` varchar(30) DEFAULT NULL,
  `PRENOM` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`IDEMPLOYE`),
  KEY `FK_ASSOCIATION_1` (`FONCTION_EMPLOYE`),
  KEY `FK_ASSOCIATION_5` (`IDSERVICE`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`IDEMPLOYE`, `FONCTION_EMPLOYE`, `IDSERVICE`, `NOM`, `PRENOM`) VALUES
(1, 'CS', 4, 'Mensier', 'Enzo'),
(3, 'AS', 1, 'Windels', 'Jean-Philippe'),
(2, 'IN', 2, 'Vachet', 'Lukas');

-- --------------------------------------------------------

--
-- Structure de la table `fonction_employe`
--

DROP TABLE IF EXISTS `fonction_employe`;
CREATE TABLE IF NOT EXISTS `fonction_employe` (
  `FONCTION_EMPLOYE` varchar(2) NOT NULL,
  `LIBELLE_TYPE_FONCTION` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`FONCTION_EMPLOYE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `fonction_employe`
--

INSERT INTO `fonction_employe` (`FONCTION_EMPLOYE`, `LIBELLE_TYPE_FONCTION`) VALUES
('CS', 'Chef de service'),
('IN', 'Infirmier'),
('AS', 'Aide soignant'),
('RI', 'Responsable Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `quantite_salle`
--

DROP TABLE IF EXISTS `quantite_salle`;
CREATE TABLE IF NOT EXISTS `quantite_salle` (
  `IDSALLE` int(11) NOT NULL,
  `IDSERVICE` int(11) NOT NULL,
  `QUANTITE_SALLE` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDSALLE`,`IDSERVICE`),
  KEY `FK_QUANTITE_SALLE2` (`IDSERVICE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `IDSALLE` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE_SALLE` varchar(2) NOT NULL,
  `IDSERVICE` int(11) NOT NULL,
  `NUMERO_SALLE` int(11) DEFAULT NULL,
  `TEMPERATURE` decimal(5,0) DEFAULT NULL,
  PRIMARY KEY (`IDSALLE`),
  KEY `FK_ASSOCIATION_3` (`TYPE_SALLE`),
  KEY `FK_ASSOCIATION_4` (`IDSERVICE`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`IDSALLE`, `TYPE_SALLE`, `IDSERVICE`, `NUMERO_SALLE`, `TEMPERATURE`) VALUES
(1, 'CM', 1, 1, '23'),
(2, 'CM', 1, 2, '22'),
(15, 'CM', 1, 2, '19'),
(13, 'BO', 3, 444, '23'),
(17, 'CM', 1, 2, '18');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `IDSERVICE` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE_SERVICE` varchar(2) NOT NULL,
  PRIMARY KEY (`IDSERVICE`),
  KEY `FK_ASSOCIATION_2` (`TYPE_SERVICE`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`IDSERVICE`, `TYPE_SERVICE`) VALUES
(1, 'UR'),
(2, 'GA'),
(3, 'CH'),
(4, 'OB'),
(5, 'PE'),
(6, 'CA');

-- --------------------------------------------------------

--
-- Structure de la table `type_salle`
--

DROP TABLE IF EXISTS `type_salle`;
CREATE TABLE IF NOT EXISTS `type_salle` (
  `TYPE_SALLE` varchar(2) NOT NULL,
  `LIBELLE_TYPE_SALLE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`TYPE_SALLE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_salle`
--

INSERT INTO `type_salle` (`TYPE_SALLE`, `LIBELLE_TYPE_SALLE`) VALUES
('CM', 'Chambre médicale'),
('BO', 'Bloc opératoire'),
('BU', 'Bureau'),
('CA', 'Cardiologie');

-- --------------------------------------------------------

--
-- Structure de la table `type_service`
--

DROP TABLE IF EXISTS `type_service`;
CREATE TABLE IF NOT EXISTS `type_service` (
  `TYPE_SERVICE` varchar(2) NOT NULL,
  `LIBELLE_TYPE_SERVICE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`TYPE_SERVICE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_service`
--

INSERT INTO `type_service` (`TYPE_SERVICE`, `LIBELLE_TYPE_SERVICE`) VALUES
('UR', 'Urologie'),
('GA', 'Gastroentérologie'),
('CH', 'Chirurgie'),
('OB', 'Obstétrique'),
('PE', 'Pédiatrie'),
('CA', 'Cardiologie');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
