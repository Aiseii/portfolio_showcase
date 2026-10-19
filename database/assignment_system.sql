CREATE DATABASE IF NOT EXISTS assignment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE assignment_system;
DROP TABLE IF EXISTS submissions;
DROP TABLE IF EXISTS assignments;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY, full_name VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL,
 role ENUM('admin','student') NOT NULL DEFAULT 'student',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY, category_name VARCHAR(100) NOT NULL UNIQUE,
 description TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE projects (
 id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, category_id INT NOT NULL,
 title VARCHAR(200) NOT NULL, description TEXT NOT NULL, tech_stack VARCHAR(255) NOT NULL,
 file_path VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);
CREATE TABLE assignments (
 id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(200) NOT NULL,
 description TEXT NOT NULL, created_by INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
);
CREATE TABLE submissions (
 id INT AUTO_INCREMENT PRIMARY KEY, assignment_id INT NOT NULL, user_id INT NOT NULL,
 file_path VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 UNIQUE KEY unique_assignment_student (assignment_id,user_id)
);

-- Demo data. Re-importing resets all five tables in assignment_system.
INSERT INTO users(id,full_name,email,password,role) VALUES
(1,'Afiq','afiq@gmail.com','$2y$12$vQF5NsLqdAJMosXQeW38gOoWq5M.g2Cwja4xH0yd1oqmVVH9cCRh6','student'),
(2,'Admin','admin@gmail.com','$2y$12$hR8RnGbcRw4RQfol7j/abOaglYAFDYlBXZVicX73joe0KcJ.Zj3tu','admin'),
(3,'Sarah','sarah.demo@example.com','$2y$12$1gkS64ObrHTYo93.tNuUCei.EPK2xh05PxZWALpgwnQtsNRLKmPvu','student'),
(4,'Haziq','haziq.demo@example.com','$2y$12$1gkS64ObrHTYo93.tNuUCei.EPK2xh05PxZWALpgwnQtsNRLKmPvu','student');

INSERT INTO categories(id,category_name,description) VALUES
(1,'Web Application','Browser-based systems built with PHP, JavaScript and MySQL'),
(2,'Mobile Application','Android and iOS applications'),
(3,'Internet of Things (IoT)','Sensor devices and connected systems'),
(4,'Cybersecurity & Networking','Network management and cybersecurity systems');

INSERT INTO projects(id,user_id,category_id,title,description,tech_stack,file_path,created_at) VALUES
(1,1,2,'ExpenseWise','Personal income and expense tracker with transaction summaries and category reports.','Flutter, Firebase, Firestore','financewise_overview.txt','2026-10-09 11:20:00'),
(2,1,1,'Campus Library Portal','A web-based application for managing books and borrowing records.','PHP, MySQL, Bootstrap','library_portal_overview.txt','2026-10-12 09:30:00'),
(3,3,3,'Smart Weather Station','IoT prototype that measures temperature and humidity for real-time monitoring.','ESP32, MQTT, HTML','weather_station_overview.txt','2026-10-15 14:15:00');

INSERT INTO assignments(id,title,description,created_by,created_at) VALUES
(1,'Web Application Planning','Submit a short description of your project objectives, problem statement and proposed features.',2,'2026-10-01 09:00:00'),
(2,'FYP Progress Report','Upload a report describing completed modules, tools used and the remaining development tasks.',2,'2026-10-05 09:00:00'),
(3,'Final Project Documentation','Submit the final report for the project, including testing and screenshots.',2,'2026-10-11 09:00:00');

INSERT INTO submissions(id,assignment_id,user_id,file_path,original_name,created_at) VALUES
(1,1,1,'afiq_assignment_1.txt','Web_Planning_Afiq.txt','2026-10-08 12:00:00'),
(2,2,1,'afiq_assignment_2.txt','FYP_Progress_Afiq.txt','2026-10-13 13:45:00'),
(3,1,3,'sarah_assignment_1.txt','Web_Planning_Sarah.txt','2026-10-14 10:10:00');
