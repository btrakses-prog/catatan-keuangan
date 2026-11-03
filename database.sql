CREATE DATABASE IF NOT EXISTS wifi_kas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wifi_kas;
CREATE TABLE IF NOT EXISTS admins(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(80) UNIQUE NOT NULL,password_hash VARCHAR(255) NOT NULL);
CREATE TABLE IF NOT EXISTS percentage_types(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 percentage DECIMAL(5,2) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS transactions(
 id INT AUTO_INCREMENT PRIMARY KEY,
 transaction_date DATE NOT NULL,
 type ENUM('in','out') NOT NULL,
 amount DECIMAL(14,2) NOT NULL,
 description VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS settings(id INT PRIMARY KEY,business_name VARCHAR(150) NOT NULL DEFAULT 'Kas WiFi');
INSERT INTO admins(username,password_hash) VALUES('admin','$2y$12$DJWmVJBsIyvdYX0t/IFVJuEcjROdyKgppu.Lc0NTkTdY6o2Ml1k8C') ON DUPLICATE KEY UPDATE username=username;
INSERT INTO settings(id,business_name) VALUES(1,'Kas WiFi') ON DUPLICATE KEY UPDATE id=id;
INSERT INTO percentage_types(name,percentage)
SELECT 'Pemilik',60 WHERE NOT EXISTS(SELECT 1 FROM percentage_types);
INSERT INTO percentage_types(name,percentage)
SELECT 'Teknisi',20 WHERE (SELECT COUNT(*) FROM percentage_types)=1;
INSERT INTO percentage_types(name,percentage)
SELECT 'Operasional',20 WHERE (SELECT COUNT(*) FROM percentage_types)=2;
