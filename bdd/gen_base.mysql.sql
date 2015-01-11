-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 05 Janvier 2015 à 13:15
-- Version du serveur :  5.6.20
-- Version de PHP :  5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `emplacement`
--

CREATE TABLE IF NOT EXISTS `emplacement` (
  `id` int(10) unsigned NOT NULL,
  `emp_used` tinyint(3) unsigned DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) unsigned NOT NULL,
  `id_emplacement` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `emplacement`
--
ALTER TABLE `emplacement`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `location`
--
ALTER TABLE `location`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `location`
 ADD CONSTRAINT fk_emp_location
 FOREIGN KEY (`id_emplacement`)
 REFERENCES `emplacement`(`id`);


--
-- Trigger
--

	-- on change le delimiteur de fin d'instruction
DELIMITER //

DROP TRIGGER IF EXISTS `trg_location_insert`;
//
	-- pour les ajout de location, maj le champ de lock
CREATE TRIGGER `trg_location_insert`
BEFORE INSERT ON `location`
FOR EACH ROW
BEGIN
		-- insertion d'une location sur un emplacement, maj du champ de lock
	IF NEW.id_emplacement > 0 THEN
		UPDATE emplacement SET
			emplacement.emp_used = 1
		WHERE emplacement.id = NEW.id_emplacement;
	END IF;
END;
//
-------------------------------------------------------
DROP TRIGGER IF EXISTS `trg_location_delete`;
//
	-- pour les fin de location, maj le champ de lock
CREATE TRIGGER `trg_location_delete`
BEFORE DELETE ON `location`
FOR EACH ROW
BEGIN
		-- delete d'une location sur un emplacement, maj du champ de lock
	IF OLD.id_emplacement > 0 THEN
		UPDATE emplacement SET
			emplacement.emp_used = 0
		WHERE emplacement.id = OLD.id_emplacement;
	END IF;
END;
//

DELIMITER ;
