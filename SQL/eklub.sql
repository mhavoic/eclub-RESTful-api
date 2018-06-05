-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2018 at 09:48 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 5.6.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eklub`
--

-- --------------------------------------------------------

--
-- Table structure for table `dobna_kategorija`
--

CREATE TABLE `dobna_kategorija` (
  `id` int(11) NOT NULL,
  `naziv` varchar(45) NOT NULL,
  `donjaDobnaGranica` tinyint(4) NOT NULL,
  `gornjaDobnaGranica` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dobna_kategorija`
--

INSERT INTO `dobna_kategorija` (`id`, `naziv`, `donjaDobnaGranica`, `gornjaDobnaGranica`) VALUES
(1, 'limači', 5, 8),
(2, 'juniori', 9, 17);

-- --------------------------------------------------------

--
-- Table structure for table `dolazak`
--

CREATE TABLE `dolazak` (
  `id` int(11) NOT NULL,
  `trening` int(11) NOT NULL,
  `sportas` int(11) NOT NULL,
  `trener` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dolazak`
--

INSERT INTO `dolazak` (`id`, `trening`, `sportas`, `trener`) VALUES
(1, 7, 71, 'joza');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `korIme` varchar(15) NOT NULL,
  `zaporka` varchar(30) NOT NULL,
  `ime` varchar(20) NOT NULL,
  `prezime` varchar(20) NOT NULL,
  `tip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`korIme`, `zaporka`, `ime`, `prezime`, `tip`) VALUES
('joza', '13123', 'joza', 'jozic', 2),
('Marko', 'test', 'Slavko', 'prezime', 1),
('pero', '12321', 'pero', 'perić', 1);

-- --------------------------------------------------------

--
-- Table structure for table `natjecanje`
--

CREATE TABLE `natjecanje` (
  `id` int(11) NOT NULL,
  `naziv` varchar(10) NOT NULL,
  `opis` varchar(45) DEFAULT NULL,
  `brojEkipa` tinyint(4) NOT NULL,
  `pocetak` date NOT NULL,
  `kraj` date NOT NULL,
  `dobnaKategorija` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `natjecanje`
--

INSERT INTO `natjecanje` (`id`, `naziv`, `opis`, `brojEkipa`, `pocetak`, `kraj`, `dobnaKategorija`) VALUES
(1, 'Marko', 'Marko_je_ovo_ažurirao', 33, '0000-00-00', '0000-00-00', 1),
(2, 'trening', 'neko lijevo natjecanje', 3, '2018-05-15', '2018-05-15', 2),
(3, 'Marko', 'Marko', 33, '0000-00-00', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ocijena`
--

CREATE TABLE `ocijena` (
  `id` int(11) NOT NULL,
  `komentar` varchar(45) DEFAULT NULL,
  `ocijena` tinyint(4) NOT NULL,
  `korisnik` varchar(15) NOT NULL,
  `trener` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ocijena`
--

INSERT INTO `ocijena` (`id`, `komentar`, `ocijena`, `korisnik`, `trener`) VALUES
(1, 'kata fakin strofa', 1, 'Marko', 'Marko'),
(2, 'jako dobar trener', 4, 'pero', 'Marko');

-- --------------------------------------------------------

--
-- Table structure for table `prijava`
--

CREATE TABLE `prijava` (
  `id` int(11) NOT NULL,
  `vrijeme` datetime NOT NULL,
  `korIme` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prijava`
--

INSERT INTO `prijava` (`id`, `vrijeme`, `korIme`) VALUES
(1, '2017-06-06 01:12:01', 'Marko');

-- --------------------------------------------------------

--
-- Table structure for table `rod`
--

CREATE TABLE `rod` (
  `SPORAS_oib` int(11) NOT NULL,
  `KORISNIK_korIme` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rod`
--

INSERT INTO `rod` (`SPORAS_oib`, `KORISNIK_korIme`) VALUES
(71, 'Marko'),
(333, 'pero');

-- --------------------------------------------------------

--
-- Table structure for table `sportas`
--

CREATE TABLE `sportas` (
  `oib` int(11) NOT NULL,
  `ime` varchar(20) NOT NULL,
  `prezime` varchar(20) NOT NULL,
  `datumRodjenja` date NOT NULL,
  `imeOca` varchar(20) DEFAULT NULL,
  `imeMajke` varchar(20) DEFAULT NULL,
  `dobnaKategorija` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sportas`
--

INSERT INTO `sportas` (`oib`, `ime`, `prezime`, `datumRodjenja`, `imeOca`, `imeMajke`, `dobnaKategorija`) VALUES
(71, 'bara', 'barica', '0000-00-00', 'stef', 'stefa', 2),
(88, 'Hrvoje', 'španja_1', '0000-00-00', 'boris', 'jasna', 1),
(333, 'Šime', 'Šimić', '2018-05-01', 'Marijan', 'Petra', 1);

-- --------------------------------------------------------

--
-- Table structure for table `statistika`
--

CREATE TABLE `statistika` (
  `id` int(11) NOT NULL,
  `minute` smallint(6) DEFAULT NULL,
  `skokoviNapad` tinyint(4) DEFAULT NULL,
  `skokoviObrana` tinyint(4) DEFAULT NULL,
  `asistencije` tinyint(4) DEFAULT NULL,
  `osobnePogreske` tinyint(4) DEFAULT NULL,
  `poeni` smallint(6) DEFAULT NULL,
  `utakmica` int(11) NOT NULL,
  `sportas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `statistika`
--

INSERT INTO `statistika` (`id`, `minute`, `skokoviNapad`, `skokoviObrana`, `asistencije`, `osobnePogreske`, `poeni`, `utakmica`, `sportas`) VALUES
(2, 0, 3, 5, 6, 7, 8, 2, 333),
(5, 99, 88, 11, 11, 11, 11, 2, 88);

-- --------------------------------------------------------

--
-- Table structure for table `tip_korisnika`
--

CREATE TABLE `tip_korisnika` (
  `id` int(11) NOT NULL,
  `naziv` varchar(10) NOT NULL,
  `opis` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tip_korisnika`
--

INSERT INTO `tip_korisnika` (`id`, `naziv`, `opis`) VALUES
(1, 'voditelj', 'voditelj sustava'),
(2, 'korisnik', 'neki opis korisnika tipa korisnik');

-- --------------------------------------------------------

--
-- Table structure for table `trenig`
--

CREATE TABLE `trenig` (
  `id` int(11) NOT NULL,
  `termin` datetime NOT NULL,
  `lokacija` varchar(40) NOT NULL,
  `dobnaKategorija` int(11) NOT NULL,
  `trener` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trenig`
--

INSERT INTO `trenig` (`id`, `termin`, `lokacija`, `dobnaKategorija`, `trener`) VALUES
(2, '0000-00-00 00:00:00', 'zagreb', 1, 'Marko'),
(3, '0000-00-00 00:00:00', 'varaždin', 2, 'Marko'),
(4, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(5, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(6, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(7, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(8, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(9, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(10, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(11, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(13, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(14, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(15, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko'),
(16, '0000-00-00 00:00:00', 'testna_lokacija', 2, 'Marko');

-- --------------------------------------------------------

--
-- Table structure for table `utakmica`
--

CREATE TABLE `utakmica` (
  `id` int(11) NOT NULL,
  `domacin` varchar(15) NOT NULL,
  `gost` varchar(15) NOT NULL,
  `lokacija` varchar(45) NOT NULL,
  `rezultat` varchar(20) DEFAULT NULL,
  `natjecanje` int(11) NOT NULL,
  `trener` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utakmica`
--

INSERT INTO `utakmica` (`id`, `domacin`, `gost`, `lokacija`, `rezultat`, `natjecanje`, `trener`) VALUES
(2, 'rijeka_1', 'varaždin_2', 'poljud', '1:150', 1, 'Marko');

-- --------------------------------------------------------

--
-- Table structure for table `vijesti`
--

CREATE TABLE `vijesti` (
  `id` int(11) NOT NULL,
  `naslov` varchar(45) NOT NULL,
  `sadrzaj` longtext,
  `voditelj` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vijesti`
--

INSERT INTO `vijesti` (`id`, `naslov`, `sadrzaj`, `voditelj`) VALUES
(2, 'drugi članak2222', 'Ovo je drugi clanak ikad objavljen u našoj aplikaciji', 'pero'),
(3, 'drugi članak', 'Ovo je drugi clanak ikad objavljen u našoj aplikaciji', 'pero'),
(4, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(5, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(6, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(7, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(8, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(9, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(10, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(11, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(12, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(13, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(14, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(15, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(16, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(18, 'testna_vijest', 'testni_sadrzaj', 'Marko'),
(19, 'testna_vijest', 'testni_sadrzaj', 'Marko');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dobna_kategorija`
--
ALTER TABLE `dobna_kategorija`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dolazak`
--
ALTER TABLE `dolazak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_DOLAZAK_TRENING1_idx` (`trening`),
  ADD KEY `fk_DOLAZAK_SPORAS1_idx` (`sportas`),
  ADD KEY `fk_dolazak_korisnik1_idx` (`trener`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`korIme`),
  ADD KEY `fk_KORISNIK_TIP_KORISNIKA1_idx` (`tip`);

--
-- Indexes for table `natjecanje`
--
ALTER TABLE `natjecanje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_NATJECANJE_DOBNA_KATEGORIJA1_idx` (`dobnaKategorija`);

--
-- Indexes for table `ocijena`
--
ALTER TABLE `ocijena`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ocijena_KORISNIK1_idx` (`korisnik`),
  ADD KEY `fk_ocijena_KORISNIK2_idx` (`trener`);

--
-- Indexes for table `prijava`
--
ALTER TABLE `prijava`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PRIJAVA_KORISNIK_idx` (`korIme`);

--
-- Indexes for table `rod`
--
ALTER TABLE `rod`
  ADD PRIMARY KEY (`SPORAS_oib`,`KORISNIK_korIme`),
  ADD KEY `fk_SPORAS_has_KORISNIK_KORISNIK1_idx` (`KORISNIK_korIme`),
  ADD KEY `fk_SPORAS_has_KORISNIK_SPORAS1_idx` (`SPORAS_oib`);

--
-- Indexes for table `sportas`
--
ALTER TABLE `sportas`
  ADD PRIMARY KEY (`oib`),
  ADD KEY `fk_SPORAS_DOBNA_KATEGORIJA1_idx` (`dobnaKategorija`);

--
-- Indexes for table `statistika`
--
ALTER TABLE `statistika`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_STAISTIKA_UTAKMICA1_idx` (`utakmica`),
  ADD KEY `fk_STAISTIKA_SPORAS1_idx` (`sportas`);

--
-- Indexes for table `tip_korisnika`
--
ALTER TABLE `tip_korisnika`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `naziv_UNIQUE` (`naziv`);

--
-- Indexes for table `trenig`
--
ALTER TABLE `trenig`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_TRENING_DOBNA_KATEGORIJA1_idx` (`dobnaKategorija`),
  ADD KEY `fk_TRENING_KORISNIK1_idx` (`trener`);

--
-- Indexes for table `utakmica`
--
ALTER TABLE `utakmica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UTAKMICA_NATJECANJE1_idx` (`natjecanje`),
  ADD KEY `fk_UTAKMICA_KORISNIK1_idx` (`trener`);

--
-- Indexes for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_VIJESTI_KORISNIK1_idx` (`voditelj`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dobna_kategorija`
--
ALTER TABLE `dobna_kategorija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dolazak`
--
ALTER TABLE `dolazak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `natjecanje`
--
ALTER TABLE `natjecanje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ocijena`
--
ALTER TABLE `ocijena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prijava`
--
ALTER TABLE `prijava`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistika`
--
ALTER TABLE `statistika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tip_korisnika`
--
ALTER TABLE `tip_korisnika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trenig`
--
ALTER TABLE `trenig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `utakmica`
--
ALTER TABLE `utakmica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vijesti`
--
ALTER TABLE `vijesti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dolazak`
--
ALTER TABLE `dolazak`
  ADD CONSTRAINT `fk_DOLAZAK_SPORAS1` FOREIGN KEY (`sportas`) REFERENCES `sportas` (`oib`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_DOLAZAK_TRENING1` FOREIGN KEY (`trening`) REFERENCES `trenig` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_dolazak_korisnik1` FOREIGN KEY (`trener`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `fk_KORISNIK_TIP_KORISNIKA1` FOREIGN KEY (`tip`) REFERENCES `tip_korisnika` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `natjecanje`
--
ALTER TABLE `natjecanje`
  ADD CONSTRAINT `fk_NATJECANJE_DOBNA_KATEGORIJA1` FOREIGN KEY (`dobnaKategorija`) REFERENCES `dobna_kategorija` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ocijena`
--
ALTER TABLE `ocijena`
  ADD CONSTRAINT `fk_ocijena_KORISNIK1` FOREIGN KEY (`korisnik`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ocijena_KORISNIK2` FOREIGN KEY (`trener`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prijava`
--
ALTER TABLE `prijava`
  ADD CONSTRAINT `fk_PRIJAVA_KORISNIK` FOREIGN KEY (`korIme`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `rod`
--
ALTER TABLE `rod`
  ADD CONSTRAINT `fk_SPORAS_has_KORISNIK_KORISNIK1` FOREIGN KEY (`KORISNIK_korIme`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SPORAS_has_KORISNIK_SPORAS1` FOREIGN KEY (`SPORAS_oib`) REFERENCES `sportas` (`oib`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sportas`
--
ALTER TABLE `sportas`
  ADD CONSTRAINT `fk_SPORAS_DOBNA_KATEGORIJA1` FOREIGN KEY (`dobnaKategorija`) REFERENCES `dobna_kategorija` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `statistika`
--
ALTER TABLE `statistika`
  ADD CONSTRAINT `fk_STAISTIKA_SPORAS1` FOREIGN KEY (`sportas`) REFERENCES `sportas` (`oib`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_STAISTIKA_UTAKMICA1` FOREIGN KEY (`utakmica`) REFERENCES `utakmica` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `trenig`
--
ALTER TABLE `trenig`
  ADD CONSTRAINT `fk_TRENING_DOBNA_KATEGORIJA1` FOREIGN KEY (`dobnaKategorija`) REFERENCES `dobna_kategorija` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_TRENING_KORISNIK1` FOREIGN KEY (`trener`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `utakmica`
--
ALTER TABLE `utakmica`
  ADD CONSTRAINT `fk_UTAKMICA_KORISNIK1` FOREIGN KEY (`trener`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UTAKMICA_NATJECANJE1` FOREIGN KEY (`natjecanje`) REFERENCES `natjecanje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD CONSTRAINT `fk_VIJESTI_KORISNIK1` FOREIGN KEY (`voditelj`) REFERENCES `korisnik` (`korIme`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
