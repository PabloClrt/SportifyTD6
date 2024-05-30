CREATE TABLE `sports` (
    `sport_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100),
    `category` VARCHAR(100),
    `coach_id` INT,
    FOREIGN KEY (`coach_id`) REFERENCES `coaches`(`coach_id`)
);

CREATE TABLE `gym_services` (
    `service_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100),
    `description` TEXT,
    `rules` TEXT,
    `schedule` TEXT,
    `responsible_contact` VARCHAR(255)
);
