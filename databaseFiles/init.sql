-- Create database scheme
CREATE TABLE users
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    is_admin BIT(1) NOT NULL,
);

CREATE TABLE groups
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE group_members
(
    group_id INT NOT NULL,
    user_id  INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES groups (id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    PRIMARY KEY (group_id, user_id)
);


-- CREATE initial admin user
INSERT INTO users (name, is_admin) VALUES ('Admin', 1);
