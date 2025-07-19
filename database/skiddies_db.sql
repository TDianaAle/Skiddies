-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 18, 2025 alle 10:19
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
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'studente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tutors`
--

INSERT INTO `tutors` (`id`, `name`, `email`, `password`, `image`, `role`) VALUES
(11, 'tutor', 'tutor@test.com', '$2y$10$lbgbSAuN4KaljSzy14j8bOckv7GVHrNFUC.7j1fYJZM', '', 'tutor'),
(12, 'ciao', 'ciao@test.com', '$2y$10$Ia1RGuZYeoW2ZicIarDHG.1vWu8UlP7.s/WhwBA.Qcn7A7FQFuZ2a', '', 'tutor'),
(13, 'test', 'test@gmail.com', '$2y$10$OG80OgiYsarsQbH5CdxClO5ctb4Eq0/CarvTEfhx.PkkmB564YLNq', '', 'tutor'),
(14, 'prova2', 'prova2@test.com', '$2y$10$wWjfYV8FHng.Vo5T4fgwlOk7Z9pOFeX.YoSxEnVogyw8hprQxtpb2', '', 'tutor'),
(15, 'Mario Rossi', 'prova4@test.com', '$2y$10$9pGA5kuwedJQEdyb.P8aFe3OSAxSn7EAekf4pNksCeHA/vorFM2qq', '687947da537cb.JPG', 'tutor'),
(16, 'tutor', 'tutor@gmail.com', '$2y$10$HegaCLB0VEXjmcuhD5nYS.9rOWHxVMI3vOw68E0WvCI5tcTv5tvsK', '', 'tutor');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(100) NOT NULL,
  `role` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `role`) VALUES
('', 'studente', 'studente@test.com', '$2y$10$xu2Jt.Cu3nl4uj/fOXRepOc4wu1tfRt/z/qfYmKCSePF92IB8cLjW', '', 'user'),
('', 'test', 'test@test.com', '$2y$10$Aitu2NGIh3ct676yH3yYy.fez9fGXPpd21Xnd/CFdyxxdSU90s5Ue', '', 'user'),
('', 'prova', 'prova@test.com', '$2y$10$x0tZYZVPgV/cdfRrKfswweELLigZEzaILez3NTJCNrNG2X0FLcGRS', '', 'user'),
('', 'prova1', 'prova1@gmail.com', '$2y$10$Lqo81m5jDrZcJx/y2MuFUeXwl0kCLT.6aQPfDW8AUhmrBU5MF.Eee', '', 'user'),
('', 'ciao', 'ciao@test.com', '$2y$10$nLqaJa5lMWLqB97V0cX1X.We4FRMAIU6udGuKrTiVjpXdyvHoE8hO', '', 'user'),
('', 'ciao', 'ciao@test.com', '$2y$10$OjvNsvfFAcVhdI4MVBA.vuO2CVAwukb8gzHEhZlp0YdJJLYU3fjCy', '', 'user'),
('', 'prova3', 'prova3@test.com', '$2y$10$rie.JEwfLjzlnJtbD7K1d.ImZydbM54qnPYCEcMoB2RXYVoUt3C7K', '', 'user'),
('', 'studente5', 'studente5@test.com', '$2y$10$D6oQW/GXR/H0mmXHNW/KsOEZhUVBxmwAF42aj6YmG..jvrjOK5DTe', '', 'user');

-- --------------------------------------------------------

--
-- Struttura della tabella `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` enum('informatica','matematica','economia') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `videos`
--

INSERT INTO `videos` (`id`, `tutor_id`, `title`, `category`, `file_path`, `uploaded_at`) VALUES
(9, 15, 'matematica', 'matematica', 'uploads/videos/68792d25b370c_182879-869766891_small.mp4', '2025-07-17 17:04:37'),
(10, 16, 'mate', 'matematica', 'uploads/videos/vid_6879e9a9e456e5.82080495_182879-869766891_small.mp4', '2025-07-18 06:28:57'),
(11, 16, 'mate', 'matematica', 'uploads/videos/vid_6879ebe3dce3e1.83159178_182879-869766891_small.mp4', '2025-07-18 06:38:27');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
