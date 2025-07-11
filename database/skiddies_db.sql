-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 10, 2025 alle 16:03
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skiddies_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `comments`
--

CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contact`
--

CREATE TABLE `contact` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(20) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `content`
--

CREATE TABLE `content` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `video` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `likes`
--

CREATE TABLE `likes` (
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `playlist`
--

CREATE TABLE `playlist` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `video` varchar(100) NOT NULL,
  `playlist_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `tutors`
--

CREATE TABLE `tutors` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'studente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tutors`
--

INSERT INTO `tutors` (`id`, `name`, `email`, `password`, `image`, `role`) VALUES
('', 'Diana', 'ola@test.com', '$2y$10$aVe0LbAyHxpxTrAAXjUXcuHgNNjWOI3EUTCwr35P7b0', 'img_6829ff3ec5eae4.18030395.jfif', 'studente'),
('', 'test', 'test@test.com', '$2y$10$t24SsbVAsOH10JkoUhVJuuzAzpYGpt9lFFGHE7x25VD', 'img_6829ff81af0e72.14192441.jpg', 'insegnante'),
('', 'Bau', 'bau@test.com', '$2y$10$28LvIgVimEvJwDgIIX1b5.bO.OUcfCX8VAJ43YS1m86', 'img_682a2b7faef310.92486315.JPG', 'insegnante'),
('', 'casa', 'casa@gmail.com', '$2y$10$oMWXHsIcmLxDh7tvc9G0HuzCXPAmg8wL2mS6j5f25f6', '', 'tutor');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `role` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `role`) VALUES
('', 'bob', 'bobby@gmail.com', '$2y$10$gdvfm8rnsuDUSjKwVZQ4sugAflAosC5y.1QGOP/tvHW', 'img_684fe602564d43.34725618.jpg', 'tutor'),
('', 'Diana due', 'dianadue@gmail.com', '$2y$10$qrlv0tjfBRbdEn41andmh.JifAH/6eApVJtF6rXKrPs', 'img_684febef824d20.89341339.JPG', 'student'),
('', 'bobb', 'bobb@gmail.com', '$2y$10$GUaPhX1MFSn1.OD5zdjiFevUfIkjZTnUn/feXgzu91a', 'img_68500794225964.15881637.JPG', 'student'),
('', 'rosa', 'rosa@gmail.com', '$2y$10$gmqGXbRpaf2osKwwj22sLuVgwKvvwC3iEafoboBfvS8Ow1RxAB60W', 'img_68500c0f3ff286.10739663.JPG', 'tutor'),
('', 'bob', 'bob@gmail.com', '$2y$10$KDlr5CSnK7wthxSkFHYi1uDSbJbYytb.tto5xq1w7WKeMadYALXau', 'img_68501af2a66700.82776886.JPG', 'student'),
('', 'sofia', 'sofia@gmail.com', '$2y$10$1yQvWUpdR.0iVfx1EZCdeenKg86HK.Smz3YwCBH8fJJyM9h82d/ZC', 'img_685027b21212b6.46587087.JPG', 'student'),
('', 'mammamia', 'mammamia@gmail.com', '$2y$10$hG/mfuPmwnyX7CL2I8jUTeHWxHFlBsGa7SFAvZscSYuf2SuWGZpFa', '', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
