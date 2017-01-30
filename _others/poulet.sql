DROP TABLE IF EXISTS `pa_army`;
CREATE TABLE IF NOT EXISTS `pa_army` (
  `idUser` int(11) NOT NULL,
  `idPoulet` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `debloque` tinyint(1) NOT NULL,
  PRIMARY KEY (`idUser`,`idPoulet`),
  KEY `FK_POULET_ID` (`idPoulet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pa_poulet`;
CREATE TABLE IF NOT EXISTS `pa_poulet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `cout` float DEFAULT NULL,
  `attaque` float DEFAULT NULL,
  `defense` float DEFAULT NULL,
  `debut` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pa_poulet` (`id`, `nom`, `image`, `cout`, `attaque`, `defense`, `debut`) VALUES
(1, 'Poulet Frêle', 'frele.png', 10, 1, 1, 1),
(2, 'Poulet Joyeux', 'joyeux.png', 45, 5, 5, 1),
(3, 'Poulet Masqué', 'masque.png', 65, 5, 8, 1),
(4, 'Poulet Fourcheur', 'fourcheur.png', 65, 8, 5, 1);

DROP TABLE IF EXISTS `pa_rapport`;
CREATE TABLE IF NOT EXISTS `pa_rapport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateAttaque` int(11) NOT NULL,
  `idDefenseur` int(11) NOT NULL,
  `idAttaquant` int(11) NOT NULL,
  `nbPouletAttaquant` int(11) NOT NULL,
  `nbPouletDefenseurPerdu` int(11) NOT NULL,
  `lu` tinyint(1) NOT NULL,
  `bois` int(11) NOT NULL,
  `graine` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pa_ressource`;
CREATE TABLE IF NOT EXISTS `pa_ressource` (
  `idUser` int(11) NOT NULL,
  `lastUpdate` int(11) NOT NULL,
  `bois` float NOT NULL,
  `scierie` int(11) NOT NULL,
  `depot` int(11) NOT NULL,
  `graine` float NOT NULL,
  `champs` int(11) NOT NULL,
  `entrepot` int(11) NOT NULL,
  `or` float NOT NULL,
  `poulailler` int(11) NOT NULL,
  `laboratoire` int(11) NOT NULL,
  UNIQUE KEY `FK_IDUSER_USERS.ID` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pa_user`;
CREATE TABLE IF NOT EXISTS `pa_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `inscription` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `PSEUDO_UNIQUE` (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `pa_army`
  ADD CONSTRAINT `FK_POULET_ID` FOREIGN KEY (`idPoulet`) REFERENCES `pa_poulet` (`id`),
  ADD CONSTRAINT `FK_USER_ID` FOREIGN KEY (`idUser`) REFERENCES `pa_user` (`id`);

ALTER TABLE `pa_ressource`
  ADD CONSTRAINT `FK_IDUSER_USERS.ID` FOREIGN KEY (`idUser`) REFERENCES `pa_user` (`id`);
