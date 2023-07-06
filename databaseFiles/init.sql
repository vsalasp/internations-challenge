-- Create database scheme
CREATE TABLE users
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE user_groups
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE user_group_members
(
    group_id INT NOT NULL,
    user_id  INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES user_groups (id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    PRIMARY KEY (group_id, user_id)
);
