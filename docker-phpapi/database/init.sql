CREATE TABLE `task` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128) NOT NULL COLLATE 'utf8mb4_general_ci',
    `priority` INT(11) NULL DEFAULT NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `name` (`name`) USING BTREE
);
INSERT INTO task (name, priority, is_completed)
VALUES
    ('Buy new shoes', 1, true),
    ('Renew passport', 2, false),
    ('Paint wall', NULL, true);

CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    username VARCHAR(128) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    api_key VARCHAR(32) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (username),
    UNIQUE (api_key)
);

ALTER TABLE task
    ADD user_id INT NOT NULL,
ADD INDEX (user_id);
ALTER TABLE task
    ADD FOREIGN KEY (user_id)
    REFERENCES user(id)
    ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE refresh_token(
    token_hash VARCHAR(64) NOT NULL,
    expires_at INT UNSIGNED NOT NULL,
    PRIMARY KEY (token_hash),
    INDEX (expires_at)
);