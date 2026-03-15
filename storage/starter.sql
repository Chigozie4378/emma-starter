create database emma_starter;
use emma_starter;
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, status)
VALUES (
    'Emma Admin',
    'admin@example.com',
    '$2y$10$vKEGSlVXFLRMKnS62cosveyU4vfP94PlYq1IYbGOaVNxn0AGwhqbe',
    'active'
);