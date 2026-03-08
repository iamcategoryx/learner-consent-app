-- MySQL Export File
-- Learner Consent Capture App
-- Absolute Training & Assessing Ltd
-- PHP 8.3 / MySQL 8.0+

CREATE DATABASE IF NOT EXISTS `learner_consent` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `learner_consent`;

DROP TABLE IF EXISTS `consent_submissions`;

CREATE TABLE `consent_submissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `sentinel_number` VARCHAR(100) NOT NULL,
  `consent` TINYINT(1) NOT NULL DEFAULT 1,
  `submitted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_sentinel` (`sentinel_number`),
  INDEX `idx_submitted_at` (`submitted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
