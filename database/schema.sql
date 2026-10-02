-- TA2 required schema. Run once in a new database.
-- No DROP commands: an existing table produces an error rather than losing data.
CREATE DATABASE IF NOT EXISTS rally_supply_ta2
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rally_supply_ta2;

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  created_at DATETIME NOT NULL
);
