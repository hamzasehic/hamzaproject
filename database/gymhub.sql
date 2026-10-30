CREATE DATABASE IF NOT EXISTS gymhub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE gymhub;


SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS classes;
DROP TABLE IF EXISTS user_memberships;
DROP TABLE IF EXISTS trainers;
DROP TABLE IF EXISTS memberships;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member') NOT NULL DEFAULT 'member',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE memberships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    duration_days INT NOT NULL,
    description VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    bio TEXT NULL,
    experience_years INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE user_memberships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    membership_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (membership_id) REFERENCES memberships(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    start_time DATETIME NOT NULL,
    capacity INT NOT NULL,
    description VARCHAR(255) NULL,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    status ENUM('booked', 'cancelled', 'completed') NOT NULL DEFAULT 'booked',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_class (user_id, class_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===== Sample data =====

-- Test accounts: admin@gymhub.com / Admin123!  and  emma@example.com / Member123!
INSERT INTO users (full_name, email, password_hash, role) VALUES
('John Smith', 'admin@gymhub.com', '$2y$10$wcJy1DZFEch6W/vHrqj9..Mqg/ah56uQLF3JmYLNmRfymLoZE3Ch6', 'admin'),
('Emma Brown', 'emma@example.com', '$2y$10$DE493VYZK7Dav57v1aN.3ufjSFfYnAp/GrL5VqVQig9jGQKiFq7ji', 'member'),
('Liam Davis', 'liam@example.com', '$2y$10$DE493VYZK7Dav57v1aN.3ufjSFfYnAp/GrL5VqVQig9jGQKiFq7ji', 'member'),
('Sara Miller', 'sara@example.com', '$2y$10$DE493VYZK7Dav57v1aN.3ufjSFfYnAp/GrL5VqVQig9jGQKiFq7ji', 'member');

INSERT INTO memberships (name, price, duration_days, description) VALUES
('Monthly', 30.00, 30, 'Gym access and locker room'),
('3 Months', 75.00, 90, 'Gym access, locker room and group classes'),
('Yearly', 250.00, 365, 'Everything included plus 2 personal training sessions');

INSERT INTO trainers (full_name, specialty, bio, experience_years) VALUES
('Amina Hadzic', 'Yoga & Pilates', 'Focused on flexibility, posture and recovery.', 8),
('Kenan Begic', 'HIIT & Cardio', 'High-energy workouts for fat loss and endurance.', 6),
('Lejla Softic', 'Strength Training', 'Builds strength programs for all levels.', 10),
('Damir Mujic', 'Boxing & Conditioning', 'Technique, speed and full-body conditioning.', 5);

INSERT INTO user_memberships (user_id, membership_id, start_date, end_date, status) VALUES
(2, 2, '2026-10-01', '2026-12-30', 'active'),
(3, 1, '2026-10-15', '2026-11-14', 'active'),
(4, 3, '2026-09-01', '2027-08-31', 'active');

INSERT INTO classes (trainer_id, name, start_time, capacity, description) VALUES
(1, 'Morning Yoga', '2026-11-02 08:00:00', 15, 'Gentle start of the week'),
(2, 'HIIT', '2026-11-02 18:00:00', 12, 'High-intensity interval training'),
(3, 'Strength Basics', '2026-11-03 10:00:00', 10, 'Intro to compound lifts'),
(4, 'Boxing', '2026-11-04 19:00:00', 8, 'Technique and conditioning'),
(3, 'Strength Basics', '2026-10-23 10:00:00', 10, 'Past class'),
(4, 'Boxing', '2026-10-21 19:00:00', 8, 'Past class');

INSERT INTO bookings (user_id, class_id, status) VALUES
(2, 1, 'booked'),
(2, 2, 'booked'),
(2, 5, 'completed'),
(2, 6, 'cancelled'),
(3, 2, 'booked'),
(3, 3, 'booked'),
(4, 1, 'booked');