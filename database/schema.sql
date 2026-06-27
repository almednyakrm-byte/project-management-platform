CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE projects (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE collaborations (
  id INT AUTO_INCREMENT,
  project_id INT NOT NULL,
  user_id INT NOT NULL,
  role ENUM('member', 'admin') NOT NULL DEFAULT 'member',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (project_id) REFERENCES projects(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE resources (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_resources (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  resource_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (resource_id) REFERENCES resources(id)
);

CREATE TABLE user_permissions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  page_name VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO projects (name, description)
VALUES ('Project 1', 'This is project 1'),
       ('Project 2', 'This is project 2');

INSERT INTO resources (name, description)
VALUES ('Resource 1', 'This is resource 1'),
       ('Resource 2', 'This is resource 2');

INSERT INTO user_permissions (user_id, page_name)
VALUES (1, 'dashboard'),
       (1, 'projects'),
       (1, 'collaborations'),
       (1, 'resources'),
       (1, 'index'),
       (1, 'login'),
       (1, 'register');

INSERT INTO user_resources (user_id, resource_id)
VALUES (1, 1),
       (1, 2);

INSERT INTO collaborations (project_id, user_id, role)
VALUES (1, 1, 'admin'),
       (2, 1, 'admin');