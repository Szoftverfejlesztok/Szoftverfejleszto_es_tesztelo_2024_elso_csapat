-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Ápr 25. 09:47
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `padlas`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `jelszo` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `fotok`
--

CREATE TABLE `fotok` (
  `fotoId` int(11) NOT NULL,
  `fotoEleresiUt` varchar(500) NOT NULL,
  `fotoDatum` datetime NOT NULL,
  `termekId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kategoria`
--

CREATE TABLE `kategoria` (
  `id` int(11) NOT NULL,
  `nev` varchar(200) NOT NULL,
  `szulo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `kategoria`
--

INSERT INTO `kategoria` (`id`, `nev`, `szulo_id`) VALUES
(1, 'Ruha', 0),
(2, 'Cipő', 0),
(3, 'Felső', 1),
(4, 'Alsó', 1),
(5, 'Egybe ruha', 1),
(6, 'Kiegészítő', 1),
(7, 'Fehérnemű', 1),
(8, 'Szabadidőcipő', 2),
(9, 'Sportcipő', 2),
(10, 'Bakancs', 2),
(11, 'Papucs', 2),
(12, 'Szandál', 2),
(13, 'Körömcipő', 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tarolok`
--

CREATE TABLE `tarolok` (
  `id` int(11) NOT NULL,
  `taroloNev` varchar(100) NOT NULL,
  `keszlet` int(11) DEFAULT 0,
  `letrehozasDatum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `termekek`
--

CREATE TABLE `termekek` (
  `id` int(11) NOT NULL,
  `letrehozas_datuma` datetime NOT NULL,
  `nem` tinyint(4) DEFAULT NULL CHECK (0 <= `nem` < 4),
  `kategoriaID` int(11) NOT NULL,
  `meret` varchar(100) NOT NULL,
  `taroloID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eseményindítók `termekek`
--
DELIMITER $$
CREATE TRIGGER `termek_rogzites` AFTER INSERT ON `termekek` FOR EACH ROW BEGIN
    IF NEW.taroloID IS NOT NULL THEN
        UPDATE tarolok
        SET keszlet = keszlet + 1
        WHERE id = NEW.taroloID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `termek_torles` AFTER DELETE ON `termekek` FOR EACH ROW BEGIN
    IF OLD.taroloID IS NOT NULL THEN
        UPDATE tarolok
        SET keszlet = keszlet - 1
        WHERE id = OLD.taroloID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `torles_termekhez_foto` AFTER DELETE ON `termekek` FOR EACH ROW BEGIN
    DELETE FROM fotok WHERE termekId = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tarolo_keszlet` AFTER UPDATE ON `termekek` FOR EACH ROW BEGIN
    IF OLD.taroloID IS NOT NULL THEN
        UPDATE `tarolok`
        SET `keszlet` = `keszlet` - 1
        WHERE `id` = OLD.taroloID;
    END IF;
    IF NEW.taroloID IS NOT NULL THEN
        UPDATE `tarolok`
        SET `keszlet` = `keszlet` + 1
        WHERE `id` = NEW.taroloID;
    END IF;
END
$$
DELIMITER ;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- A tábla indexei `fotok`
--
ALTER TABLE `fotok`
  ADD PRIMARY KEY (`fotoId`),
  ADD KEY `termekId` (`termekId`);

--
-- A tábla indexei `kategoria`
--
ALTER TABLE `kategoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nev` (`nev`);

--
-- A tábla indexei `tarolok`
--
ALTER TABLE `tarolok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `taroloNev` (`taroloNev`);

--
-- A tábla indexei `termekek`
--
ALTER TABLE `termekek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategoriaID` (`kategoriaID`),
  ADD KEY `taroloID` (`taroloID`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `fotok`
--
ALTER TABLE `fotok`
  MODIFY `fotoId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `kategoria`
--
ALTER TABLE `kategoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT a táblához `tarolok`
--
ALTER TABLE `tarolok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `termekek`
--
ALTER TABLE `termekek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `fotok`
--
ALTER TABLE `fotok`
  ADD CONSTRAINT `fotok_ibfk_1` FOREIGN KEY (`termekId`) REFERENCES `termekek` (`id`);

--
-- Megkötések a táblához `termekek`
--
ALTER TABLE `termekek`
  ADD CONSTRAINT `termekek_ibfk_1` FOREIGN KEY (`kategoriaID`) REFERENCES `kategoria` (`id`),
  ADD CONSTRAINT `termekek_ibfk_2` FOREIGN KEY (`taroloID`) REFERENCES `tarolok` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
