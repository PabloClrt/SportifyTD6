CREATE TABLE `coaches` (
    `coach_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `specialty` VARCHAR(100),
    `photo` VARCHAR(255),
    `bio` TEXT,
    `available_days` LONGTEXT,
    `office` VARCHAR(255),
    `cv` TEXT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);


CREATE TABLE specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE gyms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50)
);
