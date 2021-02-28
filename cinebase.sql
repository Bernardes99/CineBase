-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 27, 2020 at 07:20 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinebase`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `adminid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nomeadmin` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`adminid`, `email`, `password`, `nomeadmin`) VALUES
(1, 'admin@cinebase.com', 'cinebase', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `artista`
--

CREATE TABLE `artista` (
  `artistaid` int(11) NOT NULL,
  `nome` varchar(512) NOT NULL,
  `tipo` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artista`
--

INSERT INTO `artista` (`artistaid`, `nome`, `tipo`) VALUES
(1, 'Leonardo DiCaprio', 'Ator'),
(2, 'Brad Pitt', 'Ator'),
(3, 'Margot Robbie', 'Ator'),
(4, 'Quentin Tarantino', 'Director'),
(5, 'Armie Hammer', 'Ator'),
(6, 'Timothée Chalamet', 'Ator'),
(7, 'Michael Stuhlbarg', 'Ator'),
(8, 'Luca Guadagnino', 'Director'),
(9, 'Mahershala Ali', 'Ator'),
(10, 'Naomie Harris', 'Ator'),
(11, 'Trevante Rhodes', 'Ator'),
(12, 'Barry Jenkins', 'Director'),
(13, 'Tom Hanks', 'Ator'),
(14, 'Robin Wright', 'Ator'),
(15, 'Gary Sinise', 'Ator'),
(16, 'Robert Zemeckis', 'Director'),
(17, 'Robert Downey Jr.', 'Ator'),
(18, 'Chris Evans', 'Ator'),
(19, 'Mark Ruffalo', 'Ator'),
(20, 'Anthony Russo', 'Director'),
(21, 'Joe Russo', 'Director'),
(22, 'Jodie Foster', 'Ator'),
(23, 'Anthony Hopkins', 'Ator'),
(24, 'Lawrence A. Bonney', 'Ator'),
(25, 'Jonathan Demme', 'Director'),
(26, 'Marlon Brando', 'Ator'),
(27, 'Al Pacino', 'Ator'),
(28, 'James Caan', 'Ator'),
(29, 'Francis Ford Coppola', 'Director'),
(30, 'Auli\'i Cravalho', 'Ator'),
(31, 'Dwayne Johnson', 'Ator'),
(32, 'Rachel House', 'Ator'),
(33, 'Ron Clements', 'Director'),
(34, 'John Musker', 'Director'),
(35, 'Jack Nicholson', 'Ator'),
(36, 'Shelley Duvall', 'Ator'),
(37, 'Danny Lloyd', 'Ator'),
(38, 'Stanley Kubrick', 'Director'),
(39, 'Song Kang-Ho', 'Ator'),
(40, 'Lee Sun-kyun', 'Ator'),
(41, 'Cho Yeo-jeong', 'Ator'),
(42, 'Bong Joon Ho', 'Director'),
(43, 'Eddie Redmayne', 'Ator'),
(44, 'Alex Sharp', 'Ator'),
(45, 'Sacha Baron Cohen', 'Ator'),
(46, 'Aaron Sorkin', 'Director'),
(47, 'Rami Malek', 'Ator'),
(48, 'Lucy Boynton', 'Ator'),
(49, 'Gwilym Lee', 'Ator'),
(50, 'Bryan Singer', 'Director'),
(51, 'Anthony Gonzalez', 'Ator'),
(52, 'Gael García Bernal', 'Ator'),
(53, 'Benjamin Bratt', 'Ator'),
(54, 'Lee Unkrich', 'Director'),
(55, 'Ansel Elgort', 'Ator'),
(56, 'Jon Bernthal', 'Ator'),
(57, 'Jon Hamm', 'Ator'),
(58, 'Edgar Wright', 'Director'),
(59, 'John Travolta', 'Ator'),
(60, 'Uma Thurman', 'Ator'),
(61, 'Samuel L. Jackson', 'Ator'),
(62, 'Elijah Wood', 'Ator'),
(63, 'Ian McKellen', 'Ator'),
(64, 'Orlando Bloom', 'Ator'),
(65, 'Peter Jackson', 'Director');

-- --------------------------------------------------------

--
-- Table structure for table `artista_filme`
--

CREATE TABLE `artista_filme` (
  `artista_artistaid` int(11) NOT NULL,
  `filme_filmeid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artista_filme`
--

INSERT INTO `artista_filme` (`artista_artistaid`, `filme_filmeid`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 4),
(14, 4),
(15, 4),
(16, 4),
(17, 5),
(18, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 6),
(23, 6),
(24, 6),
(25, 6),
(26, 7),
(27, 7),
(28, 7),
(29, 7),
(30, 8),
(31, 8),
(32, 8),
(33, 8),
(34, 8),
(35, 9),
(36, 9),
(37, 9),
(38, 9),
(39, 10),
(40, 10),
(41, 10),
(42, 10),
(43, 11),
(44, 11),
(45, 11),
(46, 11),
(47, 12),
(48, 12),
(49, 12),
(50, 12),
(51, 13),
(52, 13),
(53, 13),
(54, 13),
(55, 14),
(56, 14),
(57, 14),
(58, 14),
(4, 15),
(59, 15),
(60, 15),
(61, 15),
(62, 17),
(63, 17),
(64, 17),
(65, 17);

-- --------------------------------------------------------

--
-- Table structure for table `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `avaliacao` float DEFAULT NULL,
  `filme_filmeid` int(11) NOT NULL,
  `utilizador_utilizadorid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `filme`
--

CREATE TABLE `filme` (
  `filmeid` int(11) NOT NULL,
  `titulo` varchar(512) NOT NULL,
  `pais` varchar(512) DEFAULT NULL,
  `imdbscore` float DEFAULT NULL,
  `sinopse` text,
  `trailerlink` varchar(512) DEFAULT NULL,
  `duracao` varchar(512) NOT NULL,
  `escondido` tinyint(1) NOT NULL,
  `ano` int(11) NOT NULL,
  `foto_nome` varchar(512) NOT NULL,
  `foto_path` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filme`
--

INSERT INTO `filme` (`filmeid`, `titulo`, `pais`, `imdbscore`, `sinopse`, `trailerlink`, `duracao`, `escondido`, `ano`, `foto_nome`, `foto_path`) VALUES
(1, 'Once Upon a Time... in Hollywood', 'USA, UK, China', 7.6, 'A faded television actor and his stunt double strive to achieve fame and success in the final years of Hollywood\'s Golden Age in 1969 Los Angeles.', 'https://www.imdb.com/video/vi1385741849?playlistId=tt7131622&ref_=tt_ov_vi', '161', 0, 2019, 'onceuponatimeinhollywood_.jpg', 'photos/onceuponatimeinhollywood_.jpg'),
(2, 'Call Me By Your Name', 'Italy, France, USA, Brazil', 7.9, 'In 1980s Italy, romance blossoms between a seventeen-year-old student and the older man hired as his father\'s research assistant.', 'https://www.imdb.com/video/vi1152171801?playlistId=tt5726616&ref_=tt_ov_vi', '132', 0, 2017, 'callmebyyourname.jpg', 'photos/callmebyyourname.jpg'),
(3, 'Moonlight', 'USA', 7.4, 'A young African-American man grapples with his identity and sexuality while experiencing the everyday struggles of childhood, adolescence, and burgeoning adulthood.', 'https://www.imdb.com/video/vi2935863321?playlistId=tt4975722&ref_=tt_ov_vi', '111', 0, 2016, 'moonlight.jpg', 'photos/moonlight.jpg'),
(4, 'Forrest Gump', 'USA', 8.8, 'The presidencies of Kennedy and Johnson, the events of Vietnam, Watergate and other historical events unfold through the perspective of an Alabama man with an IQ of 75, whose only desire is to be reunited with his childhood sweetheart.', 'https://www.imdb.com/video/vi3567517977?playlistId=tt0109830&ref_=tt_ov_vi', '144', 0, 1994, 'forrest_gump.jpg', 'photos/forrest_gump.jpg'),
(5, 'Avengers: Endgame', 'USA', 8.4, 'After the devastating events of Avengers: Infinity War (2018), the universe is in ruins. With the help of remaining allies, the Avengers assemble once more in order to reverse Thanos\' actions and restore balance to the universe.', 'https://www.youtube.com/watch?v=TcMBFSGVi1c', '181', 0, 2019, 'avengers_endgame.jpg', 'photos/avengers_endgame.jpg'),
(6, 'The Silence of the Lambs', 'USA', 8.6, 'A young F.B.I. cadet must receive the help of an incarcerated and manipulative cannibal killer to help catch another serial killer, a madman who skins his victims.', 'https://www.imdb.com/video/vi3377380121?playlistId=tt0102926&ref_=tt_ov_vi', '118', 0, 1991, 'silenceofthelambs.jpg', 'photos/silenceofthelambs.jpg'),
(7, 'The Godfather', 'USA', 9.2, 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 'https://www.imdb.com/video/vi1348706585?playlistId=tt0068646&ref_=tt_ov_vi', '175', 0, 1972, 'thegodfather.jpg', 'photos/thegodfather.jpg'),
(8, 'Moana', 'USA', 7.6, 'In Ancient Polynesia, when a terrible curse incurred by the Demigod Maui reaches Moana\'s island, she answers the Ocean\'s call to seek out the Demigod to set things right.', 'https://www.imdb.com/video/vi2813048345?playlistId=tt3521164&ref_=tt_ov_vi', '107', 0, 2016, 'moana.jpg', 'photos/moana.jpg'),
(9, 'The Shining', 'UK, USA', 8.4, 'A family heads to an isolated hotel for the winter where a sinister presence influences the father into violence, while his psychic son sees horrific forebodings from both past and future.', 'https://www.imdb.com/video/vi2689121305?playlistId=tt0081505&ref_=tt_ov_vi', '166', 0, 1980, 'thishining.jpg', 'photos/thishining.jpg'),
(10, 'Parasite', 'South Korea', 8.6, 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.', 'https://www.imdb.com/video/vi1015463705?playlistId=tt6751668&ref_=tt_ov_vi', '132', 0, 2019, 'parasite.jpg', 'photos/parasite.jpg'),
(11, 'The Trial of the Chicago 7', 'USA, UK, India', 7.8, 'The story of 7 people on trial stemming from various charges surrounding the uprising at the 1968 Democratic National Convention in Chicago, Illinois.', 'https://www.imdb.com/video/vi3081552153?playlistId=tt1070874&ref_=tt_ov_vi', '129', 0, 2020, 'trialofchicago7.jpg', 'photos/trialofchicago7.jpg'),
(12, 'Bohemian Rhapsody', 'UK, USA', 8, 'The story of the legendary British rock band Queen and lead singer Freddie Mercury, leading up to their famous performance at Live Aid (1985).', 'https://www.imdb.com/video/vi1451538969?playlistId=tt1727824&ref_=tt_ov_vi', '134', 0, 2018, 'bohemianrhapsody.jpg', 'photos/bohemianrhapsody.jpg'),
(13, 'Coco', 'USA', 8.4, 'Aspiring musician Miguel, confronted with his family\'s ancestral ban on music, enters the Land of the Dead to find his great-great-grandfather, a legendary singer.', 'https://www.imdb.com/video/vi4249729305?playlistId=tt2380307&ref_=tt_ov_vi', '105', 0, 2017, 'coco.jpg', 'photos/coco.jpg'),
(14, 'Baby Driver', 'UK, USA', 7.6, 'After being coerced into working for a crime boss, a young getaway driver finds himself taking part in a heist doomed to fail.', 'https://www.imdb.com/video/vi2482288921?playlistId=tt3890160&ref_=tt_ov_vi', '113', 0, 2017, 'babydriver.jpg', 'photos/babydriver.jpg'),
(15, 'Pulp Fiction', 'USA', 8.9, 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'https://www.imdb.com/video/vi2620371481?playlistId=tt0110912&ref_=tt_ov_vi', '154', 0, 1994, 'pulpfiction.jpg', 'photos/pulpfiction.jpg'),
(16, 'The Lord of the Rings: The Fellowship of the Ring', 'USA', 1, 'aA meek Hobbit from the Shire and eight companions set out on a journey to destroy the powerful One Ring and save Middle-earth from the Dark Lord Sauron.', 'youtube', '178', 0, 2001, 'lotr.jpg', 'photos/lotr.jpg'),
(17, 'The Lord of the Rings: The Fellowship of the Ring', 'New Zealand, USA', 8.8, 'A meek Hobbit from the Shire and eight companions set out on a journey to destroy the powerful One Ring and save Middle-earth from the Dark Lord Sauron.', 'https://www.imdb.com/video/vi2073101337?playlistId=tt0120737&ref_=tt_ov_vi', '178', 1, 2001, 'lotr.jpg', 'photos/lotr.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `genero`
--

CREATE TABLE `genero` (
  `generoid` int(11) NOT NULL,
  `generonome` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `genero`
--

INSERT INTO `genero` (`generoid`, `generonome`) VALUES
(1, 'Comedy'),
(2, 'Drama'),
(3, 'Romance'),
(4, 'Action'),
(5, 'Adventure'),
(6, 'Crime'),
(7, 'Thriller'),
(8, 'Animation'),
(9, 'Horror'),
(10, 'History'),
(11, 'Biography'),
(12, 'Music'),
(13, 'Family');

-- --------------------------------------------------------

--
-- Table structure for table `genero_filme`
--

CREATE TABLE `genero_filme` (
  `genero_generoid` int(11) NOT NULL,
  `filme_filmeid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `genero_filme`
--

INSERT INTO `genero_filme` (`genero_generoid`, `filme_filmeid`) VALUES
(1, 1),
(2, 1),
(2, 2),
(3, 2),
(2, 3),
(2, 4),
(3, 4),
(4, 5),
(5, 5),
(2, 6),
(6, 6),
(7, 6),
(2, 7),
(6, 7),
(1, 8),
(5, 8),
(8, 8),
(2, 9),
(9, 9),
(1, 10),
(7, 10),
(2, 11),
(7, 11),
(10, 11),
(2, 12),
(11, 12),
(12, 12),
(5, 13),
(8, 13),
(13, 13),
(2, 14),
(4, 14),
(6, 14),
(2, 15),
(6, 15),
(2, 17),
(4, 17),
(5, 17);

-- --------------------------------------------------------

--
-- Table structure for table `utilizador`
--

CREATE TABLE `utilizador` (
  `utilizadorid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nomeutilizador` varchar(20) NOT NULL,
  `primeironome` varchar(30) NOT NULL,
  `ultimonome` varchar(30) NOT NULL,
  `datainscricao` date NOT NULL,
  `expulso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilizador`
--

INSERT INTO `utilizador` (`utilizadorid`, `email`, `password`, `nomeutilizador`, `primeironome`, `ultimonome`, `datainscricao`, `expulso`) VALUES
(1, 'joao@gmail.com', 'joao', 'joaof', 'João', 'Ferreira', '2020-12-25', 0),
(2, 'andre@gmail.com', 'andre', 'andreb', 'André', 'Bernardes', '2020-12-27', 0),
(3, 'joana@gmail.com', 'joana', 'joanab', 'Joana', 'Baião', '2020-12-27', 0),
(4, 'filipe@gmail.com', 'filipe', 'filipea', 'Filipe', 'Amado', '2020-12-27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `utilizador_filme`
--

CREATE TABLE `utilizador_filme` (
  `utilizador_utilizadorid` int(11) NOT NULL,
  `filme_filmeid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilizador_filme`
--

INSERT INTO `utilizador_filme` (`utilizador_utilizadorid`, `filme_filmeid`) VALUES
(1, 1),
(2, 1),
(3, 2),
(1, 3),
(3, 4),
(2, 5),
(3, 6),
(1, 7),
(2, 7),
(3, 8),
(1, 9),
(1, 10),
(2, 11),
(2, 12),
(3, 12),
(3, 13),
(1, 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`adminid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nomeadmin` (`nomeadmin`);

--
-- Indexes for table `artista`
--
ALTER TABLE `artista`
  ADD PRIMARY KEY (`artistaid`);

--
-- Indexes for table `artista_filme`
--
ALTER TABLE `artista_filme`
  ADD PRIMARY KEY (`artista_artistaid`,`filme_filmeid`),
  ADD KEY `artista_filme_fk2` (`filme_filmeid`);

--
-- Indexes for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD KEY `avaliacoes_fk1` (`filme_filmeid`),
  ADD KEY `avaliacoes_fk2` (`utilizador_utilizadorid`);

--
-- Indexes for table `filme`
--
ALTER TABLE `filme`
  ADD PRIMARY KEY (`filmeid`);

--
-- Indexes for table `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`generoid`);

--
-- Indexes for table `genero_filme`
--
ALTER TABLE `genero_filme`
  ADD PRIMARY KEY (`genero_generoid`,`filme_filmeid`),
  ADD KEY `genero_filme_fk2` (`filme_filmeid`);

--
-- Indexes for table `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`utilizadorid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nomeutilizador` (`nomeutilizador`);

--
-- Indexes for table `utilizador_filme`
--
ALTER TABLE `utilizador_filme`
  ADD PRIMARY KEY (`utilizador_utilizadorid`,`filme_filmeid`),
  ADD KEY `utilizador_filme_fk2` (`filme_filmeid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artista_filme`
--
ALTER TABLE `artista_filme`
  ADD CONSTRAINT `artista_filme_fk1` FOREIGN KEY (`artista_artistaid`) REFERENCES `artista` (`artistaid`),
  ADD CONSTRAINT `artista_filme_fk2` FOREIGN KEY (`filme_filmeid`) REFERENCES `filme` (`filmeid`);

--
-- Constraints for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_fk1` FOREIGN KEY (`filme_filmeid`) REFERENCES `filme` (`filmeid`),
  ADD CONSTRAINT `avaliacoes_fk2` FOREIGN KEY (`utilizador_utilizadorid`) REFERENCES `utilizador` (`utilizadorid`);

--
-- Constraints for table `genero_filme`
--
ALTER TABLE `genero_filme`
  ADD CONSTRAINT `genero_filme_fk1` FOREIGN KEY (`genero_generoid`) REFERENCES `genero` (`generoid`),
  ADD CONSTRAINT `genero_filme_fk2` FOREIGN KEY (`filme_filmeid`) REFERENCES `filme` (`filmeid`);

--
-- Constraints for table `utilizador_filme`
--
ALTER TABLE `utilizador_filme`
  ADD CONSTRAINT `utilizador_filme_fk1` FOREIGN KEY (`utilizador_utilizadorid`) REFERENCES `utilizador` (`utilizadorid`),
  ADD CONSTRAINT `utilizador_filme_fk2` FOREIGN KEY (`filme_filmeid`) REFERENCES `filme` (`filmeid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
