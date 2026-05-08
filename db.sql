-- ======================================================
-- Student Management System - Complete Database Schema
-- ======================================================

-- Create Database (if not exists)
CREATE DATABASE IF NOT EXISTS sms_db;
USE sms_db;

-- ======================================================
-- 1. Users Table (Students, Admin, Faculty)
-- ======================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student', 'faculty') DEFAULT 'student',
    full_name VARCHAR(100) NOT NULL,
    enrollment_no VARCHAR(50) UNIQUE,
    profile_pic VARCHAR(255) DEFAULT 'default.png',
    phone VARCHAR(15),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================================================
-- 2. Courses Table
-- ======================================================
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    description TEXT,
    credits INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================================================
-- 3. Enrollments (Student <-> Course)
-- ======================================================
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- ======================================================
-- 4. Attendance
-- ======================================================
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('present', 'absent', 'late') DEFAULT 'absent',
    marked_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, course_id, date)
);

-- ======================================================
-- 5. Assignments
-- ======================================================
CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    due_date DATETIME NOT NULL,
    max_marks INT DEFAULT 100,
    file_path VARCHAR(255),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- ======================================================
-- 6. Assignment Submissions
-- ======================================================
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    student_id INT NOT NULL,
    submission_text TEXT,
    file_path VARCHAR(255),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    grade DECIMAL(5,2),
    feedback TEXT,
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================================================
-- 7. Results / Marks
-- ======================================================
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    marks DECIMAL(5,2) NOT NULL,
    grade VARCHAR(2),
    is_published BOOLEAN DEFAULT FALSE,
    exam_type VARCHAR(50) DEFAULT 'final',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_result (student_id, course_id, exam_type)
);

-- ======================================================
-- 8. Notices / Announcements
-- ======================================================
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    target_role ENUM('all', 'students', 'faculty') DEFAULT 'all',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ======================================================
-- 9. Messages / Chat
-- ======================================================
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ======================================================
-- SAMPLE DATA (for testing)
-- ======================================================

-- Insert Admin (password = 'password' hashed)
INSERT INTO users (username, email, password, role, full_name) VALUES 
('admin', 'admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator');

-- Insert Sample Student (password = 'password')
INSERT INTO users (username, email, password, role, full_name, enrollment_no) VALUES 
('student1', 'student@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'John Doe', '2024001');

-- Insert Sample Faculty (password = 'password')
INSERT INTO users (username, email, password, role, full_name) VALUES 
('faculty1', 'faculty@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'faculty', 'Prof. Jane Smith');

-- Insert Courses
INSERT INTO courses (course_code, course_name, description, credits) VALUES
('CS101', 'Introduction to Programming', 'Learn fundamentals of programming using Python', 4),
('CS102', 'Database Management Systems', 'SQL, database design, and normalization', 3),
('CS103', 'Web Development', 'HTML, CSS, JavaScript, PHP, and MySQL', 4),
('CS104', 'Data Structures', 'Arrays, Linked Lists, Stacks, Queues, Trees', 3),
('CS105', 'Software Engineering', 'Software development lifecycle, Agile, UML', 3);

-- Enroll student in courses
INSERT INTO enrollments (student_id, course_id) VALUES 
(2, 1), (2, 2), (2, 3);

-- Sample Attendance Records
INSERT INTO attendance (student_id, course_id, date, status, marked_by) VALUES
(2, 1, CURDATE(), 'present', 1),
(2, 2, CURDATE(), 'present', 1),
(2, 3, CURDATE(), 'late', 1);

-- Sample Assignment
INSERT INTO assignments (course_id, title, description, due_date, max_marks, created_by) VALUES
(1, 'Python Basics Assignment', 'Write a program to calculate factorial and Fibonacci series', DATE_ADD(NOW(), INTERVAL 7 DAY), 100, 1),
(2, 'SQL Query Exercise', 'Write 10 SQL queries on given schema', DATE_ADD(NOW(), INTERVAL 10 DAY), 50, 1);

-- Sample Notice
INSERT INTO notices (title, content, target_role, created_by) VALUES
('Welcome to New Semester', 'All students are requested to complete their course registration by Friday.', 'all', 1),
('Assignment Deadline Extended', 'The deadline for Web Development project has been extended to next week.', 'students', 1);

-- Sample Message
INSERT INTO messages (from_user_id, to_user_id, message) VALUES
(1, 2, 'Welcome to the system! Please complete your profile.');

-- Sample Result (published)
INSERT INTO results (student_id, course_id, marks, grade, is_published, exam_type, created_by) VALUES
(2, 1, 85.5, 'A', 1, 'final', 1),
(2, 2, 78.0, 'B+', 1, 'final', 1);

-- Sample Submission (for assignment 1)
INSERT INTO submissions (assignment_id, student_id, submission_text, grade) VALUES
(1, 2, 'def factorial(n): return 1 if n<=1 else n*factorial(n-1)', 90.0);

-- ======================================================
-- FINAL CHECK: Display table structures
-- ======================================================
SHOW TABLES;
CREATE DATABASE IF NOT EXISTS sms_db;
USE sms_db;
-- Then paste all the CREATE TABLE statements from the previous SQL script
GRANT ALL PRIVILEGES ON sms_db.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY,  -- same as user_id
    real_name VARCHAR(100),
    bio TEXT,
    gender VARCHAR(20),
    class VARCHAR(50),
    year VARCHAR(50),
    profile_pic VARCHAR(255),
    privacy ENUM('public','private') DEFAULT 'public',
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
);
INSERT INTO students (id, real_name, profile_pic) 
SELECT id, full_name, 'default-avatar.png' FROM users;
echo "Session user_id: " . $_SESSION['user_id'] . "<br>";
$all_users = $db->query("SELECT id, username FROM users")->fetchAll();
echo "<pre>Users in DB: "; print_r($all_users); echo "</pre>";
exit;
INSERT INTO students (id, username, real_name, bio, gender, class, year, profile_pic, privacy)
SELECT 
    id, 
    username, 
    full_name AS real_name, 
    '' AS bio, 
    '' AS gender, 
    '' AS class, 
    '' AS year, 
    COALESCE(profile_pic, 'default-avatar.png') AS profile_pic, 
    'public' AS privacy
FROM users
WHERE NOT EXISTS (SELECT 1 FROM students WHERE students.id = users.id);
SELECT id, username, email, role FROM users WHERE email = 'student@school.com';
UPDATE users SET role = 'student' WHERE email = 'student@school.com';