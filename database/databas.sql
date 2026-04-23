-- Rensa och skapa databas
DROP DATABASE IF EXISTS kvitter;
CREATE DATABASE kvitter;
USE kvitter;

-- Tabell: users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabell: kvitter
CREATE TABLE kvitter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Testanvändare (lösenord: 123456)
INSERT INTO users (username, email, password, role) VALUES 
('testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Admin (lösenord: 123456 - samma som testuser)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@kvitter.se', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Några test-kvitter
INSERT INTO kvitter (user_id, content) VALUES 
(1, 'Välkommen till Kvitter! Detta är admin.'),
(2, 'Hej världen! Mitt första kvitter.'),
(2, 'Kvitter är roligt! #test');