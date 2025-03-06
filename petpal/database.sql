CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender ENUM('boy', 'girl') NOT NULL,
    type ENUM('dog', 'cat', 'rabbit') NOT NULL,
    food INT DEFAULT 100, -- Food level (0-100)
    thirst INT DEFAULT 100, -- Thirst level (0-100)
    sleep INT DEFAULT 100, -- Sleep level (0-100)
    happiness INT DEFAULT 100, -- Happiness level (0-100)
    play INT DEFAULT 100, -- Play level (0-100)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);