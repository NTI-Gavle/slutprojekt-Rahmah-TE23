-- --------------------------------------------------------
-- Värd:                         127.0.0.1
-- Serverversion:                9.5.0 - MySQL Community Server - GPL
-- Server-OS:                    Win64
-- HeidiSQL Version:             12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumpar databasstruktur för kvitter
DROP DATABASE IF EXISTS `kvitter`;
CREATE DATABASE IF NOT EXISTS `kvitter` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kvitter`;

-- Dumpar struktur för tabell kvitter.kvitter
DROP TABLE IF EXISTS `kvitter`;
CREATE TABLE IF NOT EXISTS `kvitter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `kvitter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumpar data för tabell kvitter.kvitter: ~9 rows (ungefär)
DELETE FROM `kvitter`;
INSERT INTO `kvitter` (`id`, `user_id`, `content`, `created_at`) VALUES
	(6, 6, 'Pingooo', '2026-04-24 09:15:42'),
	(15, 2, '°‧🫧⋆.ೃ࿔*:･. ݁₊ ⊹ . ݁˖ . ݁🫧⋆｡˚', '2026-04-30 11:37:52'),
	(16, 2, '‧₊ ᵎᵎ 🍒 ⋅ ˚✮𖦹 ׂ 𓈒 🥞 ／ ⋆ ۪', '2026-04-30 11:40:06'),
	(17, 2, 'أنا مطبوخ\r\n⋆✴︎˚｡⋆', '2026-04-30 11:44:07'),
	(18, 5, 'No school for tommorrow\r\n✩°｡🧸𓏲⋆.🧺𖦹 ₊˚', '2026-04-30 11:58:12'),
	(23, 2, 'Hello', '2026-05-15 18:22:44'),
	(24, 2, 'Hello', '2026-05-15 18:26:07'),
	(25, 2, 'Hello', '2026-05-15 18:27:53'),
	(26, 2, 'Hello', '2026-05-15 18:28:07'),
	(27, 5, 'hello', '2026-05-17 21:37:03'),
	(28, 2, 'fdfdhyrdu,', '2026-05-18 07:53:23'),
	(29, 12, 'hellllloooooooo', '2026-05-18 07:54:35');

-- Dumpar struktur för tabell kvitter.likes
DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `kvitter_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_like` (`user_id`,`kvitter_id`),
  KEY `kvitter_id` (`kvitter_id`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`kvitter_id`) REFERENCES `kvitter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumpar data för tabell kvitter.likes: ~0 rows (ungefär)
DELETE FROM `likes`;
INSERT INTO `likes` (`id`, `user_id`, `kvitter_id`, `created_at`) VALUES
	(6, 2, 26, '2026-05-17 22:24:28'),
	(7, 12, 27, '2026-05-18 07:33:48'),
	(8, 12, 28, '2026-05-18 07:54:04'),
	(9, 12, 26, '2026-05-18 07:54:08'),
	(10, 12, 6, '2026-05-18 07:54:12'),
	(11, 12, 25, '2026-05-18 07:54:15'),
	(12, 12, 23, '2026-05-18 07:54:19');

-- Dumpar struktur för tabell kvitter.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumpar data för tabell kvitter.users: ~9 rows (ungefär)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
	(1, 'testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2026-04-17 09:18:21'),
	(2, 'admin', 'admin@kvitter.se', '$2y$12$Yem0r/uQa9LtiZsRl3thtu.q0Fkq1Ayd.W8clqadbFno8sYh9ysXG', 'admin', '2026-04-17 09:18:21'),
	(4, 'Ayesha', 'ayeshaaiman@gmail.com', '$2y$12$saelo9uXyFU7KKHqARLt7eGKluAtK1L6rcCKyXEKBwltS7xSQHnOa', 'user', '2026-04-23 11:44:06'),
	(5, 'Rahmah', 'rahmah@gmail.com', '$2y$12$Yem0r/uQa9LtiZsRl3thtu.q0Fkq1Ayd.W8clqadbFno8sYh9ysXG', 'user', '2026-04-24 08:39:40'),
	(6, 'Pingo', 'pingo@gmail.com', '$2y$12$WKMnUTZtOiMsY3AD0paJS.0q9tE8s16rWtLVBQE3ajaNKpl8ed6Ee', 'user', '2026-04-24 09:14:56'),
	(7, 'Tanjiro', 'tanjiro@gmail.com', '$2y$12$yLiK4RttTzXwWIWbJJ.7WOMmWv2KyIyeQicfTRwyw4vB7p8uFIsFS', 'user', '2026-04-27 12:25:36'),
	(8, 'Abdulle', 'Abdi@gmail.com', '$2y$12$/QaOaqtD71TSyGlE/sWMTucVmMFLSqhytKBhfvvvy0YPyN7iGlkQ2', 'user', '2026-04-30 11:26:25'),
	(9, 'A1234', 'A1234@gmail.com', '$2y$12$7MN3B24dypwKqsbieSLh0O/CWZNBzhNmjMCNl12bGUva7jWqJERCG', 'user', '2026-05-11 07:22:54'),
	(10, 'sohyun12', 'sohyun12@gmail.com', '$2y$12$uCyo0qgw3rVyupz0/UN7MeRqSu36yRFgGfiiR3Qr5q7YHET6vrPEy', 'user', '2026-05-12 12:18:07'),
	(11, 'Mina', 'Mina@gmail.com', '$2y$12$7mLPFkHTbqM2nY6WUyllTeKJQQs2Jy7ebmjYbtGsdGPN.dZBdtQ/.', 'user', '2026-05-17 22:31:11'),
	(12, 'user', 'user@gmail.com', '$2y$12$C1ztO2AyCnosu4pShsCAA.oqYGHH.WGu.oG7Twjw6w2A9IaDd2CzC', 'user', '2026-05-18 07:32:55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
