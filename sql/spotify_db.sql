-- ===== Reinicio seguro =====
DROP DATABASE IF EXISTS `spotify_db`;
CREATE DATABASE IF NOT EXISTS `spotify_db`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;
USE `spotify_db`;

-- Por si vienes de una corrupción anterior:
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `queries`;
DROP TABLE IF EXISTS `canciones`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ===== Tabla: users =====
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `password_hash` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_users_username` (`username`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Usuario de prueba para que index.html tenga opciones desde el minuto 1
INSERT INTO `users` (`username`, `email`) VALUES ('Demo', 'demo@example.com');

-- ===== Tabla: canciones =====
-- Mantenemos fecha_lanzamiento como VARCHAR(20) porque Spotify puede devolver
-- YYYY / YYYY-MM / YYYY-MM-DD.
CREATE TABLE `canciones` (
  `id` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `artista` VARCHAR(255) NOT NULL,
  `album` VARCHAR(255) DEFAULT NULL,
  `fecha_lanzamiento` VARCHAR(20) DEFAULT NULL,
  `popularidad` INT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_canciones_artista` (`artista`),
  KEY `idx_canciones_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===== Tabla: queries =====
-- release_date también como VARCHAR(20) para ser consistente con `canciones`.
CREATE TABLE `queries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `song_name` VARCHAR(255) DEFAULT NULL,
  `artist_name` VARCHAR(255) DEFAULT NULL,
  `album_name` VARCHAR(255) DEFAULT NULL,
  `release_date` VARCHAR(20) DEFAULT NULL,
  `popularity` INT DEFAULT NULL,
  `queried_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_queries_user` (`user_id`),
  KEY `idx_queries_artist` (`artist_name`),
  CONSTRAINT `fk_queries_user`
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
