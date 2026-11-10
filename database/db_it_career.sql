-- =========================================================================
-- Skema Database: db_it_career
-- Tugas Praktikum DPBO Modul 8 - Arsitektur Web PHP OOP & PDO Prepared Statements
-- Universitas Pendidikan Indonesia
--
-- Struktur Relasi Antar Entitas (3 Tabel):
-- 1. `tracks` (Entitas Induk 1)      : Jalur karir spesialisasi IT.
-- 2. `courses` (Entitas Anak 1)     : Kursus teknis, berelasi FK `track_id` -> `tracks.id`.
-- 3. `enrollments` (Entitas Anak 2) : Talenta siswa, berelasi FK `course_id` -> `courses.id`.
-- Aturan Integritas Referensial     : ON DELETE CASCADE & ON UPDATE CASCADE.
-- =========================================================================

CREATE DATABASE IF NOT EXISTS `db_it_career`;
USE `db_it_career`;

-- 1. Tabel tracks (Jalur Karir IT)
DROP TABLE IF EXISTS `enrollments`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `tracks`;

CREATE TABLE `tracks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `track_name` VARCHAR(100) NOT NULL,
  `career_prospect` VARCHAR(150) NOT NULL,
  `estimated_salary` VARCHAR(60) NOT NULL,
  `difficulty_level` ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL DEFAULT 'Beginner',
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel courses (Kursus / Pelatihan)
CREATE TABLE `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `track_id` INT NOT NULL,
  `course_name` VARCHAR(150) NOT NULL,
  `tech_stack` VARCHAR(150) NOT NULL,
  `duration_weeks` INT NOT NULL,
  `mentor_name` VARCHAR(100) NOT NULL,
  `portfolio_project` VARCHAR(200) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_course_track` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel enrollments (Siswa / Talenta IT)
CREATE TABLE `enrollments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `student_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `target_role` VARCHAR(100) NOT NULL,
  `readiness_status` ENUM('Learning', 'Building Portfolio', 'Interview Ready', 'Hired') NOT NULL DEFAULT 'Learning',
  `github_url` VARCHAR(150) DEFAULT NULL,
  `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_enrollment_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data Awal
INSERT INTO `tracks` (`id`, `track_name`, `career_prospect`, `estimated_salary`, `difficulty_level`, `description`) VALUES
(1, 'Backend Engineering', 'Junior Backend Developer, API Engineer', 'Rp 7.000.000 - Rp 14.000.000', 'Intermediate', 'Mempelajari pembuatan REST API, query database relasional, caching, serta arsitektur backend yang aman dan scalable.'),
(2, 'Frontend Development', 'Frontend Developer, UI Engineer', 'Rp 6.000.000 - Rp 12.000.000', 'Beginner', 'Fokus pada pembangunan antarmuka web modern, manajemen state, konsumsi API, dan responsivitas tampilan.'),
(3, 'DevOps & Cloud', 'DevOps Engineer, Cloud Administrator', 'Rp 9.000.000 - Rp 17.000.000', 'Intermediate', 'Membahas otomatisasi deployment (CI/CD), pengelolaan container Docker, dan dasar manajemen server Linux di cloud.'),
(4, 'Data Science & Analytics', 'Junior Data Analyst, Data Engineer', 'Rp 8.000.000 - Rp 15.000.000', 'Advanced', 'Mengolah data menggunakan Python dan SQL untuk kebutuhan eksplorasi, visualisasi, dan pemodelan prediktif.');

INSERT INTO `courses` (`id`, `track_id`, `course_name`, `tech_stack`, `duration_weeks`, `mentor_name`, `portfolio_project`) VALUES
(1, 1, 'RESTful API dengan Golang & PostgreSQL', 'Go, PostgreSQL, Gin, Docker', 8, 'Rian Hidayat', 'API Marketplace dengan Autentikasi JWT'),
(2, 1, 'Modern PHP & Laravel Web Development', 'PHP 8.2, Laravel, MySQL', 6, 'Budi Setiawan', 'Sistem Manajemen Inventaris Gudang'),
(3, 2, 'React & TypeScript untuk Web Enterprise', 'React, TypeScript, Bootstrap 5', 8, 'Siti Rahma', 'Dashboard Monitoring Keuangan Interaktif'),
(4, 3, 'Fundamental Docker & CI/CD Pipeline', 'Docker, GitHub Actions, Linux', 6, 'Ahmad Fajar', 'Automated Deployment Pipeline Web App'),
(5, 4, 'Analisis Data & Prediksi Bisnis dengan Python', 'Python, Pandas, Scikit-Learn', 10, 'Dimas Anggara', 'Model Prediksi Churn Pelanggan Telco');

INSERT INTO `enrollments` (`id`, `course_id`, `student_name`, `email`, `target_role`, `readiness_status`, `github_url`) VALUES
(1, 1, 'Farhan Pratama', 'farhan.pratama@mhs.upi.edu', 'Junior Backend Developer', 'Building Portfolio', 'https://github.com/farhanpratama/api-marketplace'),
(2, 2, 'Nadia Amalia', 'nadia.amalia@gmail.com', 'Junior Web Developer', 'Interview Ready', 'https://github.com/nadiaamalia/inventaris-laravel'),
(3, 3, 'Aditya Maulana', 'aditya.maulana@gmail.com', 'Frontend Engineer', 'Hired', 'https://github.com/adityamaulana/react-dashboard'),
(4, 4, 'Rifqi Ramadhan', 'rifqi.ramadhan@mhs.upi.edu', 'DevOps Engineer', 'Learning', 'https://github.com/rifqiramadhan/docker-ci-pipeline'),
(5, 5, 'Dinda Maharani', 'dinda.maharani@gmail.com', 'Junior Data Analyst', 'Interview Ready', 'https://github.com/dindamaharani/telco-churn-analysis');
