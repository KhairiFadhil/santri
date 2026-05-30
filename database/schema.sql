-- SANTRI Belajar - Schema (versi simpel)

CREATE DATABASE IF NOT EXISTS santri_belajar
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE santri_belajar;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS queues;
DROP TABLE IF EXISTS doctor_off;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS poli;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;


-- pasien
CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nik             VARCHAR(16) NOT NULL UNIQUE,
    name            VARCHAR(120) NOT NULL,
    email           VARCHAR(120) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    phone           VARCHAR(20),
    birth           DATE,
    gender          ENUM('Laki-laki','Perempuan'),
    address         TEXT,
    insurance_type  ENUM('BPJS','Asuransi','Umum') DEFAULT 'Umum',
    insurance_no    VARCHAR(40),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- staff (admin / petugas / manajer)
CREATE TABLE staff (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    email         VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('admin','petugas','manajer', 'dokter') NOT NULL DEFAULT 'petugas',
    doctors_id    INT UNSIGNED NULL,
    status        ENUM('online','break','offline') DEFAULT 'offline',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- poli
CREATE TABLE poli (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code       VARCHAR(3) NOT NULL UNIQUE,
    name       VARCHAR(60) NOT NULL,
    sub        VARCHAR(120),
    icon       VARCHAR(40) DEFAULT 'Stethoscope',
    is_open    TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- dokter
CREATE TABLE doctors (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    poli_id        INT UNSIGNED NOT NULL,
    name           VARCHAR(120) NOT NULL,
    specialization VARCHAR(80),
    is_active      TINYINT(1) DEFAULT 1,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_doctor_poli FOREIGN KEY (poli_id) REFERENCES poli(id) ON DELETE RESTRICT
) ENGINE=InnoDB;


-- jadwal mingguan
CREATE TABLE schedules (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id   INT UNSIGNED NOT NULL,
    day_of_week ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
    time_start  TIME NOT NULL,
    time_end    TIME NOT NULL,
    capacity    INT UNSIGNED DEFAULT 30,
    CONSTRAINT fk_schedule_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_doctor_day (doctor_id, day_of_week)
) ENGINE=InnoDB;


-- libur dadakan dokter (per tanggal)
CREATE TABLE doctor_off (
    doctor_id INT UNSIGNED NOT NULL,
    off_date  DATE NOT NULL,
    PRIMARY KEY (doctor_id, off_date),
    CONSTRAINT fk_off_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- tiket antrean
CREATE TABLE queues (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_code     VARCHAR(16) NOT NULL,
    number          INT UNSIGNED NOT NULL,
    user_id         INT UNSIGNED NULL,
    walkin_name     VARCHAR(120),
    walkin_nik      VARCHAR(16),
    walkin_phone    VARCHAR(20),
    doctor_id       INT UNSIGNED NOT NULL,
    poli_id         INT UNSIGNED NOT NULL,
    schedule_date   DATE NOT NULL,
    schedule_time   TIME,
    complaint       TEXT,
    status          ENUM('wait','call','progress','done','skip','cancel') NOT NULL DEFAULT 'wait',
    insurance_type  ENUM('BPJS','Asuransi','Umum') DEFAULT 'Umum',
    registered_via  ENUM('online','walkin') DEFAULT 'online',
    handled_by      INT UNSIGNED NULL,
    called_at       TIMESTAMP NULL,
    started_at      TIMESTAMP NULL,
    completed_at    TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_queue_user   FOREIGN KEY (user_id)    REFERENCES users(id)   ON DELETE SET NULL,
    CONSTRAINT fk_queue_doctor FOREIGN KEY (doctor_id)  REFERENCES doctors(id) ON DELETE RESTRICT,
    CONSTRAINT fk_queue_poli   FOREIGN KEY (poli_id)    REFERENCES poli(id)    ON DELETE RESTRICT,
    CONSTRAINT fk_queue_staff  FOREIGN KEY (handled_by) REFERENCES staff(id)   ON DELETE SET NULL,
    UNIQUE KEY uniq_doctor_date_number (doctor_id, schedule_date, number),
    INDEX idx_queue_poli_date_status (poli_id, schedule_date, status),
    INDEX idx_queue_user_status (user_id, status),
    INDEX idx_queue_date (schedule_date)
) ENGINE=InnoDB;
