
-- Généré le :  Lun 03 Septembre 2020 à 14:47
-- Version du serveur :  5.7.27-log
-- Version de PHP :  7.0.33-0+deb9u6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbs181440`
--

-- --------------------------------------------------------

--
-- Structure de la table `gbaf_actor`
--

CREATE TABLE `gbaf_actor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `logo_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gbaf_comment`
--

CREATE TABLE `gbaf_comment` (
  `id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_add` datetime NOT NULL,
  `gbaf_actor_id` int(11) NOT NULL,
  `gbaf_member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gbaf_member`
--

CREATE TABLE `gbaf_member` (
  `id` int(11) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `gbaf_vote`
--

CREATE TABLE `gbaf_vote` (
  `id` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  `gbaf_actor_id` int(11) NOT NULL,
  `gbaf_member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `gbaf_actor`
--
ALTER TABLE `gbaf_actor`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gbaf_comment`
--
ALTER TABLE `gbaf_comment`
  ADD PRIMARY KEY (`id`,`gbaf_actor_id`,`gbaf_member_id`),
  ADD KEY `fk_gbaf_comment_gbaf_actor1_idx` (`gbaf_actor_id`),
  ADD KEY `fk_gbaf_comment_gbaf_member1_idx` (`gbaf_member_id`);

--
-- Index pour la table `gbaf_member`
--
ALTER TABLE `gbaf_member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Index pour la table `gbaf_vote`
--
ALTER TABLE `gbaf_vote`
  ADD PRIMARY KEY (`id`,`gbaf_actor_id`,`gbaf_member_id`),
  ADD KEY `fk_gbaf_vote_gbaf_actor1_idx` (`gbaf_actor_id`),
  ADD KEY `fk_gbaf_vote_gbaf_member1_idx` (`gbaf_member_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `gbaf_actor`
--
ALTER TABLE `gbaf_actor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `gbaf_comment`
--
ALTER TABLE `gbaf_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT pour la table `gbaf_member`
--
ALTER TABLE `gbaf_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `gbaf_vote`
--
ALTER TABLE `gbaf_vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `gbaf_comment`
--
ALTER TABLE `gbaf_comment`
  ADD CONSTRAINT `fk_gbaf_comment_gbaf_actor1` FOREIGN KEY (`gbaf_actor_id`) REFERENCES `gbaf_actor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gbaf_comment_gbaf_member1` FOREIGN KEY (`gbaf_member_id`) REFERENCES `gbaf_member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `gbaf_vote`
--
ALTER TABLE `gbaf_vote`
  ADD CONSTRAINT `fk_gbaf_vote_gbaf_actor1` FOREIGN KEY (`gbaf_actor_id`) REFERENCES `gbaf_actor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gbaf_vote_gbaf_member1` FOREIGN KEY (`gbaf_member_id`) REFERENCES `gbaf_member` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
