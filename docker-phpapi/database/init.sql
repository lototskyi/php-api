CREATE DATABASE api_db;
GRANT ALL PRIVILEGES ON api_db.* TO appuser@'%' IDENTIFIED BY 'apppassword';
USE api_db;
CREATE TABLE `task` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128) NOT NULL COLLATE 'utf8mb4_general_ci',
    `priority` INT(11) NULL DEFAULT NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `name` (`name`) USING BTREE
);