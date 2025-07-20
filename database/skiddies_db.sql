-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--a
-- Host: 127.0.0.1
-- Creato il: Lug 21, 2025 alle 01:11
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
  `id` int(11) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `user_id`, `tutor_id`, `comment`, `date`) VALUES
(1, '1', '1', '16', 'molto utile, grazie!', '2025-07-20'),
(2, '1', '1', '16', 'bene!', '2025-07-20'),
(3, '2', '1', '16', 'benissimo', '2025-07-20'),
(4, '1', '1', '16', 'molto utile, ho imparato tanto in poco tempo!', '2025-07-20'),
(5, '6', '1', '18', 'ottimo per principianti!', '2025-07-20'),
(6, '7', '1', '18', 'bene!', '2025-07-20'),
(7, '7', '1', '18', 'ottimo!!!!', '2025-07-20');

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

--
-- Dump dei dati per la tabella `likes`
--

INSERT INTO `likes` (`user_id`, `tutor_id`, `content_id`) VALUES
('16', '16', '29'),
('16', '16', '30'),
('1', '16', '29'),
('1', '16', '30'),
('1', '16', '32'),
('1', '16', '1'),
('1', '16', '2'),
('1', '16', '3'),
('1', '16', '4'),
('1', '16', '5'),
('1', '18', '6'),
('1', '18', '7');

-- --------------------------------------------------------

--
-- Struttura della tabella `playlist`
--

CREATE TABLE `playlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `playlist`
--

INSERT INTO `playlist` (`id`, `user_id`, `video_id`, `added_at`) VALUES
(1, 1, 1, '2025-07-21 00:50:53'),
(2, 1, 2, '2025-07-21 00:51:01');

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
(16, 'tutor', 'tutor@gmail.com', '$2y$10$HegaCLB0VEXjmcuhD5nYS.9rOWHxVMI3vOw68E0WvCI5tcTv5tvsK', '', 'tutor'),
(17, 'prova7', 'prova7@test.com', '$2y$10$/aURU9W4LkW1z81oAumayeX0ZCr.kXztjDfkjMHUWfnTe4hLcYwa6', '', 'tutor'),
(18, 'diana', 'diana@test.com', '$2y$10$EdAApwNRYiWwAKfka1hWkeaFxmeEBL1/uSCD3yxaeTbMqju2oU/Ke', '', 'tutor');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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
(1, 'student', 'student@test.com', '$2y$10$hZvJ27V5/AbBXOFjXq4LJOoDM3QPIsBIAkyNUjKAttZbZkHY0lyE2', '', 'user');

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
(1, 16, 'Calcolo differenziale', 'matematica', 'uploads/videos/vid_687d0e0bcbfd21.54296359_182879-869766891_small.mp4', '2025-07-20 15:40:59'),
(2, 16, 'economia per tutti', 'economia', 'uploads/videos/vid_687d1194cad831.69102032_182879-869766891_small.mp4', '2025-07-20 15:56:04'),
(3, 16, 'informatica per bambini', 'informatica', 'uploads/videos/vid_687d168af1fbe8.39377266_182879-869766891_small.mp4', '2025-07-20 16:17:14'),
(4, 16, 'informatica per adulti', 'informatica', 'uploads/videos/vid_687d17f73f0673.75247579_182879-869766891_small.mp4', '2025-07-20 16:23:19'),
(5, 16, 'programmazione html, js, css', 'informatica', 'uploads/videos/vid_687d2e2a787f86.98473200_182879-869766891_small.mp4', '2025-07-20 17:58:02'),
(6, 18, 'python per skiddies', 'informatica', 'uploads/videos/vid_687d407657c1f1.88377674_182879-869766891_small.mp4', '2025-07-20 19:16:06'),
(7, 18, 'matematica per sciocchi', 'matematica', 'uploads/videos/vid_687d439c331014.68817470_182879-869766891_small.mp4', '2025-07-20 19:29:32');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indici per le tabelle `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
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
-- AUTO_INCREMENT per la tabella `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `playlist`
--
ALTER TABLE `playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `playlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `playlist_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
