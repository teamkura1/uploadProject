SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE TABLE `accounts` (
  `id` text NOT NULL,
  `isModerator` tinyint NOT NULL DEFAULT '0',
  `christmasSkinOwned` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `files` (
  `id` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` tinytext NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtype` tinytext NOT NULL,
  `tag` tinytext,
  `goldenFile` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `filesizecache` (
  `name` tinytext NOT NULL,
  `size` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `sends` (
  `id` int NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file` tinytext NOT NULL,
  `sentFor` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
ALTER TABLE `files`
  ADD UNIQUE KEY `nameIndex` (`name`(60)),
  ADD KEY `tagIndex` (`tag`(60));
ALTER TABLE `filesizecache`
  ADD UNIQUE KEY `name` (`name`(60));
ALTER TABLE `sends`
  ADD KEY `sendsIdIndex` (`id`),
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;