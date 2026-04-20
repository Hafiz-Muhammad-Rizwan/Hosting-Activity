-- ============================================================
-- OffTheField - Sports Career Platform
-- Database Setup Script
-- ============================================================

CREATE DATABASE IF NOT EXISTS offthefield_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE offthefield_db;

-- ------------------------------------------------------------
-- Users Table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    username    VARCHAR(80)      NOT NULL,
    email       VARCHAR(180)     NOT NULL UNIQUE,
    password    VARCHAR(255)     NOT NULL,    -- bcrypt hash
    role        ENUM('student','organization','admin') NOT NULL DEFAULT 'student',
    created_at  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Seed demo accounts  (password = "password123" for all)
-- Generated with: password_hash('password123', PASSWORD_BCRYPT)
-- ------------------------------------------------------------
INSERT INTO users (username, email, password, role) VALUES
('Alex Johnson',   'alex@offthefield.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Coach Rivera',   'coach@offthefield.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'organization'),
('Admin User',     'admin@offthefield.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
