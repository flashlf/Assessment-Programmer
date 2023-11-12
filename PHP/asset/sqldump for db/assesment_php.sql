-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2023 at 06:08 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assesment_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `todo_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `todo_id`, `status`, `description`) VALUES
(1, 1, 0, 'Task 1 dong brooo'),
(2, 1, 1, 'Test Lagi dari api untuk update'),
(3, 1, 0, 'Task 1 dong brooo'),
(4, 1, 0, 'Task 1 dong brooo'),
(5, 1, 0, 'Task ke-n dong brooo'),
(6, 1, 0, 'Task ke-n dong brooo');

--
-- Triggers `tasks`
--
DELIMITER $$
CREATE TRIGGER `update_todo_status` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN
    DECLARE total_completed_tasks INT;
    DECLARE total_tasks INT;
    
    -- Menghitung jumlah tugas yang selesai (status = 1) untuk todo_id yang sama
    SELECT COUNT(*) INTO total_completed_tasks
    FROM tasks
    WHERE todo_id = NEW.todo_id AND status = 1;

    -- Menghitung total jumlah tugas untuk todo_id yang sama
    SELECT COUNT(*) INTO total_tasks
    FROM tasks
    WHERE todo_id = NEW.todo_id;

    -- Memperbarui status pada tabel "todos" berdasarkan hasil perhitungan
    IF total_completed_tasks = total_tasks THEN
        UPDATE todos
        SET status = 1
        WHERE todo_id = NEW.todo_id;
    ELSE
        UPDATE todos
        SET status = 0
        WHERE todo_id = NEW.todo_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `todo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 1,
  `title` varchar(64) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `image_attachment` varchar(1024) DEFAULT NULL,
  `background` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `todos`
--

INSERT INTO `todos` (`todo_id`, `user_id`, `status`, `title`, `description`, `image_attachment`, `background`) VALUES
(1, 1, 0, 'Todo 1', 'Dummy Tugas 1 bror', NULL, 'FF00FF'),
(2, 1, 0, 'Adul', 'Test Lagi dari api untuk update', '\\asset\\uploads\\6551212214631.png', 'FFFFFF'),
(6, 1, 0, 'Adul', 'Test', '\\asset\\uploads\\65514be0d090e.png', ''),
(7, 1, 0, 'Adul', 'Test', '', ''),
(8, 1, 0, 'Dari Ui', 'Gatau bisa apa gak guyss ', '', ''),
(9, 1, 0, 'Lagi', 'Dari ui lagi ', '', ''),
(10, 1, 0, 'Lagi lagi', 'Sekarang harusnya nutup sih ', '', ''),
(11, 1, 0, 'New Ampere', 'Teknologi baru dalam penggunaan resistor, dapat memuat ampere hingga 1Kilowatt pada abad 21 ini. hal ini ditemukan oleh seorang ilmuwan dari Indonesia. ', '', '');

--
-- Triggers `todos`
--
DELIMITER $$
CREATE TRIGGER `before_todos_delete` BEFORE DELETE ON `todos` FOR EACH ROW BEGIN
    DELETE FROM tasks WHERE todo_id = OLD.todo_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `level` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `code`, `email`, `name`, `password`, `level`) VALUES
(1, 'john_doe', 'john@example.com', 'John Doe', '$2y$10$5lErbLkEUxQ1i/GwvrV.guzXh3BEAqEwRIudp3CXZFOJHI6TJclye', 1),
(2, 'jane_smith', 'jane@example.com', 'Jane Smith', NULL, 2),
(3, 'mark_johnson', 'mark@example.com', 'Mark Johnson', NULL, 2),
(6, 'test', 'test@local.com', 'test', '$2y$10$bguveLHAq/.lha1U6GIPoe92UJx2uxdH.X5FAWXWN635MZay9sZ5.', 2),
(7, 'adul', 'adul@mail.com', 'Adul', '$2y$10$Oti76og/gQ.tlnSGZ2v7iumGePXspPlTGtIKT00eyEjfc28KMRQy6', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

CREATE TABLE `user_levels` (
  `level_id` smallint(6) NOT NULL,
  `description` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`level_id`, `description`) VALUES
(1, 'Admin'),
(2, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`todo_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `user_levels`
--
ALTER TABLE `user_levels`
  ADD PRIMARY KEY (`level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `todo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
