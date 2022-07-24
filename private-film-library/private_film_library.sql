-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 25. čec 2022, 01:40
-- Verze serveru: 10.4.22-MariaDB
-- Verze PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `private_film_library`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `creators`
--

CREATE TABLE `creators` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `country` varchar(255) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `creators`
--

INSERT INTO `creators` (`id`, `first_name`, `last_name`, `date_of_birth`, `country`, `type_id`) VALUES
(5, 'Tom', 'Hanks', '1956-07-09', 'USA', 2),
(8, 'Robert', 'Zemeckis', '1951-12-05', 'USA', 1),
(9, 'Quentin', 'Tarantino', '1963-03-27', 'USA', 1),
(10, 'John', 'Travolta', '1954-02-18', 'USA', 2),
(11, 'Samuel L.', 'Jackson', '1948-12-21', 'USA', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `creators_type`
--

CREATE TABLE `creators_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `creators_type`
--

INSERT INTO `creators_type` (`id`, `name`) VALUES
(1, 'Director'),
(2, 'Actor'),
(3, 'Cameraman');

-- --------------------------------------------------------

--
-- Struktura tabulky `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `length` int(11) NOT NULL,
  `landscape` varchar(255) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `film`
--

INSERT INTO `film` (`id`, `title`, `length`, `landscape`, `genre_id`, `country`) VALUES
(21, 'Pulp Fiction', 154, 'City', 1, 'USA'),
(22, 'Forrest Gump', 142, 'City', 3, 'USA');

-- --------------------------------------------------------

--
-- Struktura tabulky `fi_cr`
--

CREATE TABLE `fi_cr` (
  `film_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `fi_cr`
--

INSERT INTO `fi_cr` (`film_id`, `creator_id`) VALUES
(22, 5),
(22, 8),
(21, 9),
(21, 10),
(21, 11);

-- --------------------------------------------------------

--
-- Struktura tabulky `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `genre`
--

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'Action film'),
(2, 'Animated film'),
(3, 'Comedy film');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `creators`
--
ALTER TABLE `creators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexy pro tabulku `creators_type`
--
ALTER TABLE `creators_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexy pro tabulku `fi_cr`
--
ALTER TABLE `fi_cr`
  ADD PRIMARY KEY (`creator_id`,`film_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indexy pro tabulku `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `creators`
--
ALTER TABLE `creators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pro tabulku `creators_type`
--
ALTER TABLE `creators_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pro tabulku `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `creators`
--
ALTER TABLE `creators`
  ADD CONSTRAINT `creators_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `creators_type` (`id`);

--
-- Omezení pro tabulku `film`
--
ALTER TABLE `film`
  ADD CONSTRAINT `film_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`);

--
-- Omezení pro tabulku `fi_cr`
--
ALTER TABLE `fi_cr`
  ADD CONSTRAINT `fi_cr_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `creators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fi_cr_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `film` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
