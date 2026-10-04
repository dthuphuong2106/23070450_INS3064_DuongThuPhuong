-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 04, 2026 lúc 03:01 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `university_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `credits` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `credits`, `department_id`, `instructor_id`) VALUES
(1, 'IS101', 'Database Management', 3, 1, 1),
(2, 'CS101', 'Introduction to Programming', 4, 2, 2),
(3, 'BA101', 'Principles of Management', 3, 3, 3),
(4, 'ACC101', 'Financial Accounting', 3, 4, 4),
(5, 'MKT101', 'Digital Marketing', 3, 5, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `created_at`) VALUES
(1, 'Information Systems', 'IS', 'Information systems and business technology', '2026-10-04 19:59:27'),
(2, 'Computer Science', 'CS', 'Computer science and software development', '2026-10-04 19:59:27'),
(3, 'Business Administration', 'BA', 'Business and management', '2026-10-04 19:59:27'),
(4, 'Accounting', 'ACC', 'Accounting and auditing', '2026-10-04 19:59:27'),
(5, 'Marketing', 'MKT', 'Marketing and digital communication', '2026-10-04 19:59:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `grade` decimal(4,2) DEFAULT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `semester_id`, `grade`) VALUES
(1, 1, 1, 1, 8.50),
(2, 1, 2, 2, 7.80),
(3, 1, 3, 3, 9.00),
(4, 2, 2, 1, 8.00),
(5, 2, 1, 2, 7.50),
(6, 2, 4, 3, 8.20),
(7, 3, 1, 4, 9.20),
(8, 3, 5, 4, 8.80),
(9, 4, 3, 5, 7.00),
(10, 5, 5, 5, 9.50);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `instructor_code` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `instructors`
--

INSERT INTO `instructors` (`id`, `instructor_code`, `full_name`, `email`, `gender`, `department_id`) VALUES
(1, 'INS001', 'Nguyen Van An', 'an.nguyen@university.edu', 'Male', 1),
(2, 'INS002', 'Tran Thi Binh', 'binh.tran@university.edu', 'Female', 2),
(3, 'INS003', 'Le Quang Huy', 'huy.le@university.edu', 'Male', 3),
(4, 'INS004', 'Pham Thu Ha', 'ha.pham@university.edu', 'Female', 4),
(5, 'INS005', 'Hoang Minh Duc', 'duc.hoang@university.edu', 'Male', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `semesters`
--

INSERT INTO `semesters` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, 'Fall 2024', '2024-09-01', '2024-12-31'),
(2, 'Spring 2025', '2025-01-15', '2025-05-31'),
(3, 'Fall 2025', '2025-09-01', '2025-12-31'),
(4, 'Spring 2026', '2026-01-15', '2026-05-31'),
(5, 'Fall 2026', '2026-09-01', '2026-12-31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_code` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT 0.00,
  `department_id` int(11) NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `students`
--

INSERT INTO `students` (`id`, `student_code`, `full_name`, `email`, `gender`, `date_of_birth`, `gpa`, `department_id`) VALUES
(1, 'SV001', 'Nguyen Minh Anh', 'minhanh@gmail.com', 'Female', '2005-03-15', 3.50, 1),
(2, 'SV002', 'Tran Duc Minh', 'ducminh@gmail.com', 'Male', '2004-07-20', 3.20, 2),
(3, 'SV003', 'Le Thu Trang', 'thutrang@gmail.com', 'Female', '2005-01-10', 3.80, 1),
(4, 'SV004', 'Pham Quoc Bao', 'quocbao@gmail.com', 'Male', '2004-11-25', 2.90, 3),
(5, 'SV005', 'Hoang Ngoc Lan', 'ngoclan@gmail.com', 'Female', '2005-06-08', 3.60, 5);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_course_code` (`course_code`),
  ADD KEY `fk_course_department` (`department_id`),
  ADD KEY `fk_course_instructor` (`instructor_id`);

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_department_name` (`name`),
  ADD UNIQUE KEY `uq_department_code` (`code`);

--
-- Chỉ mục cho bảng `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`student_id`,`course_id`,`semester_id`),
  ADD KEY `fk_enrollment_course` (`course_id`),
  ADD KEY `fk_enrollment_semester` (`semester_id`);

--
-- Chỉ mục cho bảng `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_instructor_code` (`instructor_code`),
  ADD UNIQUE KEY `uq_instructor_email` (`email`),
  ADD KEY `fk_instructor_department` (`department_id`);

--
-- Chỉ mục cho bảng `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_semester_name` (`name`);

--
-- Chỉ mục cho bảng `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_code` (`student_code`),
  ADD UNIQUE KEY `uq_student_email` (`email`),
  ADD KEY `fk_student_department` (`department_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_course_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enrollment_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollment_semester` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enrollment_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `fk_instructor_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_student_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
