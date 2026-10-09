/*
Name: Duong Thu Phuong
Student ID: 23070450
Assignment: Homework 5 - SQL Queries
Date: 2026-10-09
*/

USE university_db;

/*
 * Query 1: List All Students
 * Description: Retrieves all columns from the students table
 * Expected Output: Five student records with their personal and academic information
 */
SELECT *
FROM students;
/*
Explanation:
SELECT *: retrieves all columns
FROM students: retrieves data from the 'students' table

Result:
+----+--------------+-----------------+--------------------+--------+---------------+------+---------------+
| id | student_code | full_name       | email              | gender | date_of_birth | gpa  | department_id |
+----+--------------+-----------------+--------------------+--------+---------------+------+---------------+
|  1 | SV001        | Nguyen Minh Anh | minhanh@gmail.com  | Female | 2005-03-15    | 3.50 |             1 |
|  2 | SV002        | Tran Duc Minh   | ducminh@gmail.com  | Male   | 2004-07-20    | 3.20 |             2 |
|  3 | SV003        | Le Thu Trang    | thutrang@gmail.com | Female | 2005-01-10    | 3.80 |             1 |
|  4 | SV004        | Pham Quoc Bao   | quocbao@gmail.com  | Male   | 2004-11-25    | 2.90 |             3 |
|  5 | SV005        | Hoang Ngoc Lan  | ngoclan@gmail.com  | Female | 2005-06-08    | 3.60 |             5 |
+----+--------------+-----------------+--------------------+--------+---------------+------+---------------+
*/

/*
 * Query 2: Students with GPA Above 3.0
 * Description: Filters students whose GPA is greater than 3.0
 * Expected Output: Four students with GPA above 3.0
 */
SELECT student_code, full_name, gpa
FROM students
WHERE gpa > 3.0;
/*
Explanation: WHERE gpa > 3.0 retains only those students who meet the condition
Result:
+--------------+-----------------+------+
| student_code | full_name       | gpa  |
+--------------+-----------------+------+
| SV001        | Nguyen Minh Anh | 3.50 |
| SV002        | Tran Duc Minh   | 3.20 |
| SV003        | Le Thu Trang    | 3.80 |
| SV005        | Hoang Ngoc Lan  | 3.60 |
+--------------+-----------------+------+
*/

/*
 * Query 3: Courses with Multiple Conditions
 * Description: Selects courses from departments 1 or 2 that have at least 3 credits
 * Expected Output: Two courses with their codes and credits
 */
SELECT course_code, course_name, credits
FROM courses
WHERE (department_id = 1 OR department_id = 2)
  AND credits >= 3;

/*Explanation:
OR: The course belongs to Department 1 or Department 2
AND: The course must simultaneously carry at least 3 credits
Parentheses are used to determine the order in which conditions are combined

Result:
+-------------+-----------------------------+---------+
| course_code | course_name                 | credits |
+-------------+-----------------------------+---------+
| IS101       | Database Management         |       3 |
| CS101       | Introduction to Programming |       4 |
+-------------+-----------------------------+---------+
*/

/*
 * Query 4: Students Ranked by GPA
 * Description: Sorts students by GPA in descending order
 * Expected Output: Five students ordered from highest
 * GPA to lowest GPA
 */
SELECT student_code, full_name, gpa
FROM students
ORDER BY gpa DESC;

/*Explanation:
ORDER BY: sorts the results
DESC: descending order; use ASC for ascending order
Result:
+--------------+-----------------+------+
| student_code | full_name       | gpa  |
+--------------+-----------------+------+
| SV003        | Le Thu Trang    | 3.80 |
| SV005        | Hoang Ngoc Lan  | 3.60 |
| SV001        | Nguyen Minh Anh | 3.50 |
| SV002        | Tran Duc Minh   | 3.20 |
| SV004        | Pham Quoc Bao   | 2.90 |
+--------------+-----------------+------+
*/

/*
 * Query 5: Top Three Enrollment Grades
 * Description: Displays the three highest enrollment grades
 * Expected Output: Three enrollment records ranked from highest grade to lowest grade
 */
SELECT
    student_id,
    course_id,
    semester_id,
    grade
FROM enrollments
ORDER BY grade DESC
LIMIT 3;

/*Explanation:
ORDER BY grade DESC: sorts grades from highest to lowest
LIMIT 3: returns only the first 3 rows

Result:
+------------+-----------+-------------+-------+
| student_id | course_id | semester_id | grade |
+------------+-----------+-------------+-------+
|          5 |         5 |           5 |  9.50 |
|          3 |         1 |           4 |  9.20 |
|          1 |         3 |           3 |  9.00 |
+------------+-----------+-------------+-------+
*/

/*
 * Query 6: Search Courses by Title
 * Description: Finds courses whose names contain the word "Introduction"
 * Expected Output: Courses matching the search pattern
 */
SELECT course_code, course_name
FROM courses
WHERE course_name LIKE '%Introduction%';

/*Explanation:
LIKE: searches for data based on a pattern
%Introduction%: the word "Introduction" can appear at the beginning, middle, or end of the course name
Result:
+-------------+-----------------------------+
| course_code | course_name                 |
+-------------+-----------------------------+
| CS101       | Introduction to Programming |
+-------------+-----------------------------+
*/

/*
 * Query 7: Count Students per Department
 * Description: Counts the students assigned to each department
 * LEFT JOIN also includes departments with no students
 * Expected Output: Five departments and their student counts
 */
SELECT
    d.name AS department_name,
    COUNT(s.id) AS total_students
FROM departments d
LEFT JOIN students s
    ON d.id = s.department_id
GROUP BY d.id, d.name
ORDER BY d.id;

/*Explanation:
COUNT(s.id): counts the students belonging to each department
LEFT JOIN: ensures departments with no students are still displayed
GROUP BY: groups records by department
AS: assigns a more readable name to the result column

Result:
+-------------------------+----------------+
| department_name         | total_students |
+-------------------------+----------------+
| Information Systems     |              2 |
| Computer Science        |              1 |
| Business Administration |              1 |
| Accounting              |              0 |
| Marketing               |              1 |
+-------------------------+----------------+
*/

/*
 * Query 8: Students Enrolled in Multiple Courses
 * Description: Groups enrollments by student and keeps only students with more than one enrollment
 * Expected Output: Three students and their enrollment counts
 */
SELECT
    s.student_code,
    s.full_name,
    COUNT(e.id) AS total_courses
FROM students s
JOIN enrollments e
    ON s.id = e.student_id
GROUP BY s.id, s.student_code, s.full_name
HAVING COUNT(e.id) > 1
ORDER BY s.id;

/*Explanation:
GROUP BY: groups registrations by student
COUNT(e.id): counts the number of registrations for each student
HAVING COUNT(e.id) > 1: retains only those groups with more than one registration
Key difference: WHERE filters individual rows before grouping, whereas HAVING filters groups after the GROUP BY operation is performed
Result:
+--------------+-----------------+---------------+
| student_code | full_name       | total_courses |
+--------------+-----------------+---------------+
| SV001        | Nguyen Minh Anh |             3 |
| SV002        | Tran Duc Minh   |             3 |
| SV003        | Le Thu Trang    |             2 |
+--------------+-----------------+---------------+
*/

/*
 * Query 9: Courses with Instructor Names
 * Description: Joins courses and instructors using the instructor_id foreign key
 * Expected Output: Five courses with instructor names
 */
SELECT
    c.course_code,
    c.course_name,
    i.full_name AS instructor_name
FROM courses c
JOIN instructors i
    ON c.instructor_id = i.id
ORDER BY c.course_code;

/*Explanation:
courses c: assigns the alias 'c' to the 'courses' table
instructors i: assigns the alias 'i' to the 'instructors' table
JOIN: combines the two tables using the 'instructor_id' foreign key
AS instructor_name: assigns a readable name to the instructor name column

Result:
+-------------+-----------------------------+-----------------+
| course_code | course_name                 | instructor_name |
+-------------+-----------------------------+-----------------+
| ACC101      | Financial Accounting        | Pham Thu Ha     |
| BA101       | Principles of Management    | Le Quang Huy    |
| CS101       | Introduction to Programming | Tran Thi Binh   |
| IS101       | Database Management         | Nguyen Van An   |
| MKT101      | Digital Marketing           | Hoang Minh Duc  |
+-------------+-----------------------------+-----------------+
*/

/*
 * Query 10: Complete Enrollment Details
 * Description: Joins enrollments with students, courses, and semesters to show complete registration information
 * Expected Output: Ten enrollment records with student names, course titles, semester names, and grades.
 */

SELECT
    s.full_name AS student_name,
    c.course_name,
    sem.name AS semester_name,
    e.grade
FROM enrollments e
JOIN students s
    ON e.student_id = s.id
JOIN courses c
    ON e.course_id = c.id
JOIN semesters sem
    ON e.semester_id = sem.id
ORDER BY e.id;

/*Explanation:
The `enrollments` table stores linking IDs rather than the actual names of students, courses, and semesters. Therefore, we must perform a JOIN with the three corresponding tables to retrieve human-readable information

Result:
+-----------------+-----------------------------+---------------+-------+
| student_name    | course_name                 | semester_name | grade |
+-----------------+-----------------------------+---------------+-------+
| Nguyen Minh Anh | Database Management         | Fall 2024     |  8.50 |
| Nguyen Minh Anh | Introduction to Programming | Spring 2025   |  7.80 |
| Nguyen Minh Anh | Principles of Management    | Fall 2025     |  9.00 |
| Tran Duc Minh   | Introduction to Programming | Fall 2024     |  8.00 |
| Tran Duc Minh   | Database Management         | Spring 2025   |  7.50 |
| Tran Duc Minh   | Financial Accounting        | Fall 2025     |  8.20 |
| Le Thu Trang    | Database Management         | Spring 2026   |  9.20 |
| Le Thu Trang    | Digital Marketing           | Spring 2026   |  8.80 |
| Pham Quoc Bao   | Principles of Management    | Fall 2026     |  7.00 |
| Hoang Ngoc Lan  | Digital Marketing           | Fall 2026     |  9.50 |
+-----------------+-----------------------------+---------------+-------+
*/
