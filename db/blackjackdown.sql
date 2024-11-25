-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Jul 2024 um 13:14
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `blackjackdown`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bosse`
--

CREATE TABLE `bosse` (
  `BossID` int(11) NOT NULL,
  `Name` varchar(80) NOT NULL,
  `Trefferpunkte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `bosse`
--

INSERT INTO `bosse` (`BossID`, `Name`, `Trefferpunkte`) VALUES
(1, 'Wild Bill Killock', 500),
(2, 'Billy the Kuhn', 600),
(3, 'Omarkun', 400);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `session`
--

CREATE TABLE `session` (
  `SessionID` int(11) NOT NULL,
  `UserNr` int(11) NOT NULL,
  `BossNr` int(11) NOT NULL,
  `Schaden` int(11) NOT NULL,
  `Sieg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `session`
--

INSERT INTO `session` (`SessionID`, `UserNr`, `BossNr`, `Schaden`, `Sieg`) VALUES
(1, 2, 1, 345, 0),
(2, 2, 2, 200, 0),
(3, 2, 3, 1000, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(80) NOT NULL,
  `Password` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`) VALUES
(2, 'Sven', '$2y$10$1e/egfb5Yg5x/HYvYMhwg.Hv2xp9dP6qS6ZUlqTJ//YjLc1PvPxMi'),
(3, 'heinz', '$2y$10$h0SpQkDqkE/TzG7TaU64cO4zhjnk1OT/HY8Tmyvql4EDo6PV5.m56');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bosse`
--
ALTER TABLE `bosse`
  ADD PRIMARY KEY (`BossID`),
  ADD KEY `BossID` (`BossID`);

--
-- Indizes für die Tabelle `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`SessionID`),
  ADD KEY `BossNr` (`BossNr`),
  ADD KEY `UserNr` (`UserNr`),
  ADD KEY `SessionID` (`SessionID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `UserID` (`UserID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bosse`
--
ALTER TABLE `bosse`
  MODIFY `BossID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `session`
--
ALTER TABLE `session`
  MODIFY `SessionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`BossNr`) REFERENCES `bosse` (`BossID`),
  ADD CONSTRAINT `session_ibfk_2` FOREIGN KEY (`UserNr`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
