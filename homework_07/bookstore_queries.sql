
/*
Name: Duong Thu Phuong
Student ID: 23070450
Assignment: Homework 7 - SQL Queries
Date: 2026-10-23
Database: bookstore_db
*/

USE bookstore_db;

-- Query 1: Books with Authors
-- Description: Lists each book with its ISBN and author name.
-- Books with multiple authors appear once for each author.

SELECT
    b.title AS book_title,
    b.isbn,
    a.author_name
FROM books b
INNER JOIN book_authors ba
    ON b.book_id = ba.book_id
INNER JOIN authors a
    ON ba.author_id = a.author_id
ORDER BY b.title, a.author_name;

/*
Expected Output:
+------------------------------------------+---------------+------------------+
| book_title                               | isbn          | author_name      |
+------------------------------------------+---------------+------------------+
| 1984                                     | 9780451524935 | George Orwell    |
| Animal Farm                              | 9780451526342 | George Orwell    |
| Harry Potter and the Chamber of Secrets  | 9780439064873 | J.K. Rowling     |
| Harry Potter and the Philosopher's Stone | 9780747532699 | J.K. Rowling     |
| Murder on the Orient Express              | 9780062693662 | Agatha Christie  |
| Pride and Prejudice                      | 9780141439518 | Jane Austen      |
| Sapiens                                  | 9780062316097 | Yuval Noah Harari|
| The Alchemist                            | 9780062315007 | Paulo Coelho     |
+------------------------------------------+---------------+------------------+
(8 rows, based on the sample data)
*/

-- Query 2: Order Details
-- Description: Displays each order with the customer name, order date,
-- book title, quantity, and line total. Newest orders appear first.

SELECT
    o.order_id,
    c.customer_name,
    o.order_date,
    b.title AS book_title,
    oi.quantity,
    (oi.quantity * oi.unit_price) AS line_total
FROM orders o
INNER JOIN customers c
    ON o.customer_id = c.customer_id
INNER JOIN order_items oi
    ON o.order_id = oi.order_id
INNER JOIN books b
    ON oi.book_id = b.book_id
ORDER BY o.order_date DESC, o.order_id DESC, b.title;

/*
Expected Output:
+----------+----------------+---------------------+------------------------------------------+----------+------------+
| order_id | customer_name  | order_date          | book_title                               | quantity | line_total |
+----------+----------------+---------------------+------------------------------------------+----------+------------+
| 6        | Nguyen Minh Anh| 2026-10-06 08:45:00 | Harry Potter and the Chamber of Secrets  | 1        | 16.50      |
| 6        | Nguyen Minh Anh| 2026-10-06 08:45:00 | Murder on the Orient Express              | 1        | 9.99       |
| 5        | Hoang Ngoc Lan  | 2026-10-05 16:20:00 | Sapiens                                  | 1        | 18.50      |
| 5        | Hoang Ngoc Lan  | 2026-10-05 16:20:00 | The Alchemist                            | 1        | 9.99       |
| 4        | Pham Quoc Bao   | 2026-10-04 11:15:00 | 1984                                     | 1        | 10.00      |
| 4        | Pham Quoc Bao   | 2026-10-04 11:15:00 | Animal Farm                              | 2        | 19.98      |
| 3        | Le Thu Trang    | 2026-10-03 14:00:00 | Murder on the Orient Express              | 1        | 11.99      |
| 3        | Le Thu Trang    | 2026-10-03 14:00:00 | Pride and Prejudice                      | 1        | 2.00       |
| 3        | Le Thu Trang    | 2026-10-03 14:00:00 | The Alchemist                            | 1        | 13.25      |
| 2        | Tran Duc Minh   | 2026-10-02 10:30:00 | Harry Potter and the Philosopher's Stone | 1        | 15.99      |
| 2        | Tran Duc Minh   | 2026-10-02 10:30:00 | Sapiens                                  | 1        | 16.50      |
| 1        | Nguyen Minh Anh | 2026-10-01 09:00:00 | 1984                                     | 1        | 12.99      |
| 1        | Nguyen Minh Anh | 2026-10-01 09:00:00 | Pride and Prejudice                      | 1        | 10.50      |
+----------+----------------+---------------------+------------------------------------------+----------+------------+
(13 rows, based on the sample data)
*/

-- Query 3: Books with Publisher and Categories
-- Description: Lists each book with its publisher and category names.
-- Books assigned to multiple categories appear in multiple rows.

SELECT
    b.title AS book_title,
    p.publisher_name,
    c.category_name
FROM books b
INNER JOIN publishers p
    ON b.publisher_id = p.publisher_id
INNER JOIN book_categories bc
    ON b.book_id = bc.book_id
INNER JOIN categories c
    ON bc.category_id = c.category_id
ORDER BY b.title, c.category_name;

/*
Expected Output:
+------------------------------------------+-------------------------+---------------+
| book_title                               | publisher_name          | category_name |
+------------------------------------------+-------------------------+---------------+
| 1984                                     | Penguin Random House    | Fiction       |
| 1984                                     | Penguin Random House    | Mystery       |
| Animal Farm                              | Penguin Random House    | Fiction       |
| Harry Potter and the Chamber of Secrets  | Macmillan Publishers    | Fantasy       |
| Harry Potter and the Philosopher's Stone | HarperCollins           | Fantasy       |
| Murder on the Orient Express              | HarperCollins           | Mystery       |
| Pride and Prejudice                      | Penguin Random House    | Fiction       |
| Sapiens                                  | Macmillan Publishers    | History       |
| The Alchemist                            | Simon & Schuster        | Fiction       |
| The Alchemist                            | Simon & Schuster        | Self-Help     |
+------------------------------------------+-------------------------+---------------+
(10 rows, based on the sample data)
*/

-- Query 4: Sales by Book
-- Description: Calculates the total quantity sold and total revenue
-- for each book, sorted by revenue from highest to lowest.

SELECT
    b.book_id,
    b.title AS book_title,
    COALESCE(SUM(oi.quantity), 0) AS total_quantity_sold,
    COALESCE(SUM(oi.quantity * oi.unit_price), 0.00) AS total_revenue
FROM books b
LEFT JOIN order_items oi
    ON b.book_id = oi.book_id
GROUP BY b.book_id, b.title
ORDER BY total_revenue DESC, b.title;

/*
Expected Output:
+---------+------------------------------------------+---------------------+--------------+
| book_id | book_title                               | total_quantity_sold| total_revenue|
+---------+------------------------------------------+---------------------+--------------+
| 4       | Sapiens                                  | 2                   | 35.00        |
| 5       | The Alchemist                            | 2                   | 23.24        |
| 1       | 1984                                     | 2                   | 22.99        |
| 6       | Murder on the Orient Express              | 2                   | 21.98        |
| 7       | Animal Farm                              | 2                   | 19.98        |
| 8       | Harry Potter and the Chamber of Secrets  | 1                   | 16.50        |
| 3       | Harry Potter and the Philosopher's Stone | 1                   | 15.99        |
| 2       | Pride and Prejudice                      | 2                   | 12.50        |
+---------+------------------------------------------+---------------------+--------------+
(8 rows, based on the sample data)
*/

-- Query 5: Customer Order Summary
-- Description: Shows each customer's number of orders and total spending.
-- Customers without orders are included with zero orders and zero spending.

SELECT
    c.customer_id,
    c.customer_name,
    COUNT(DISTINCT o.order_id) AS number_of_orders,
    COALESCE(SUM(oi.quantity * oi.unit_price), 0.00) AS total_spent
FROM customers c
LEFT JOIN orders o
    ON c.customer_id = o.customer_id
LEFT JOIN order_items oi
    ON o.order_id = oi.order_id
GROUP BY c.customer_id, c.customer_name
ORDER BY c.customer_id;

/*
Expected Output:
+-------------+-----------------+-----------------+-------------+
| customer_id | customer_name   | number_of_orders| total_spent |
+-------------+-----------------+-----------------+-------------+
| 1           | Nguyen Minh Anh | 2               | 49.98       |
| 2           | Tran Duc Minh   | 1               | 32.49       |
| 3           | Le Thu Trang    | 1               | 27.24       |
| 4           | Pham Quoc Bao   | 1               | 29.98       |
| 5           | Hoang Ngoc Lan  | 1               | 28.49       |
+-------------+-----------------+-----------------+-------------+
(5 rows, based on the sample data)
*/

-- Query 6: Books Above Average Price
-- Description: Finds books whose prices are higher than the average
-- price of all books in the database.

SELECT
    title AS book_title,
    price
FROM books
WHERE price > (
    SELECT AVG(price)
    FROM books
)
ORDER BY price DESC;

/*
Expected Output:
+------------------------------------------+-------+
| book_title                               | price |
+------------------------------------------+-------+
| Sapiens                                  | 18.50 |
| Harry Potter and the Chamber of Secrets  | 16.50 |
| Harry Potter and the Philosopher's Stone | 15.99 |
+------------------------------------------+-------+
(3 rows, based on the sample data)
*/

-- Query 7: Best-Selling Author
-- Description: Finds the author or authors whose books have the
-- highest total quantity sold. A subquery handles ties.

SELECT
    a.author_name,
    SUM(oi.quantity) AS total_quantity_sold
FROM authors a
INNER JOIN book_authors ba
    ON a.author_id = ba.author_id
INNER JOIN order_items oi
    ON ba.book_id = oi.book_id
GROUP BY a.author_id, a.author_name
HAVING SUM(oi.quantity) = (
    SELECT MAX(author_sales.total_quantity)
    FROM (
        SELECT
            ba2.author_id,
            SUM(oi2.quantity) AS total_quantity
        FROM book_authors ba2
        INNER JOIN order_items oi2
            ON ba2.book_id = oi2.book_id
        GROUP BY ba2.author_id
    ) AS author_sales
)
ORDER BY a.author_name;

/*
Expected Output:
+---------------+--------------------+
| author_name   | total_quantity_sold|
+---------------+--------------------+
| George Orwell | 4                  |
+---------------+--------------------+
(1 row, based on the sample data)
*/

-- Query 8: Prolific Reviewers
-- Description: Finds customers who have written more than two reviews
-- with ratings of 3 or higher.

SELECT
    c.customer_name,
    COUNT(r.review_id) AS number_of_reviews
FROM customers c
INNER JOIN reviews r
    ON c.customer_id = r.customer_id
WHERE r.rating >= 3
GROUP BY c.customer_id, c.customer_name
HAVING COUNT(r.review_id) > 2
ORDER BY number_of_reviews DESC, c.customer_name;

/*
Expected Output:
+---------------+------------------+
| customer_name | number_of_reviews|
+---------------+------------------+
| No rows       |                  |
+---------------+------------------+
(0 rows, based on the sample data)

Note: Each customer currently has two reviews with ratings of 3 or higher,
so no customer satisfies the condition of having more than two reviews.
*/

-- Query 9: Create View v_book_sales_summary
-- Description: Creates a view summarizing sales and customer reviews
-- for every book, including books without sales or reviews.

CREATE OR REPLACE VIEW v_book_sales_summary AS
SELECT
    b.title AS book_title,
    b.isbn,
    p.publisher_name,
    COALESCE(s.total_quantity_sold, 0) AS total_quantity_sold,
    COALESCE(s.total_revenue, 0.00) AS total_revenue,
    r.average_rating,
    COALESCE(r.number_of_reviews, 0) AS number_of_reviews
FROM books b
INNER JOIN publishers p
    ON b.publisher_id = p.publisher_id
LEFT JOIN (
    SELECT
        book_id,
        SUM(quantity) AS total_quantity_sold,
        SUM(quantity * unit_price) AS total_revenue
    FROM order_items
    GROUP BY book_id
) AS s
    ON b.book_id = s.book_id
LEFT JOIN (
    SELECT
        book_id,
        AVG(rating) AS average_rating,
        COUNT(review_id) AS number_of_reviews
    FROM reviews
    GROUP BY book_id
) AS r
    ON b.book_id = r.book_id;

/*
Expected Output:
Run the following SELECT statement to display the view.

+------------------------------------------+---------------+-------------------------+---------------------+--------------+---------------+------------------+
| book_title                               | isbn          | publisher_name          | total_quantity_sold | total_revenue| average_rating | number_of_reviews|
+------------------------------------------+---------------+-------------------------+---------------------+--------------+---------------+------------------+
| 1984                                     | 9780451524935 | Penguin Random House    | 2                   | 22.99        | 4.5000        | 2                |
| Pride and Prejudice                      | 9780141439518 | Penguin Random House    | 2                   | 12.50        | 4.0000        | 1                |
| Harry Potter and the Philosopher's Stone | 9780747532699 | HarperCollins           | 1                   | 15.99        | 5.0000        | 2                |
| Sapiens                                  | 9780062316097 | Macmillan Publishers    | 2                   | 35.00        | 5.0000        | 1                |
| The Alchemist                            | 9780062315007 | Simon & Schuster        | 2                   | 23.24        | 4.0000        | 1                |
| Murder on the Orient Express              | 9780062693662 | HarperCollins           | 2                   | 21.98        | 4.0000        | 1                |
| Animal Farm                              | 9780451526342 | Penguin Random House    | 2                   | 19.98        | 5.0000        | 1                |
| Harry Potter and the Chamber of Secrets  | 9780439064873 | Macmillan Publishers    | 1                   | 16.50        | 5.0000        | 1                |
+------------------------------------------+---------------+-------------------------+---------------------+--------------+---------------+------------------+
(8 rows, based on the sample data)
*/

-- Display the view's contents:
SELECT *
FROM v_book_sales_summary
ORDER BY book_title;

-- Query 10: Monthly Revenue Report
-- Description: Reports the number of orders, items sold, and revenue
-- for every month in the current year, including months with no sales.

WITH RECURSIVE months AS (
    SELECT 1 AS month_number
    UNION ALL
    SELECT month_number + 1
    FROM months
    WHERE month_number < 12
),
monthly_sales AS (
    SELECT
        MONTH(o.order_date) AS month_number,
        COUNT(DISTINCT o.order_id) AS number_of_orders,
        SUM(oi.quantity) AS total_items_sold,
        SUM(oi.quantity * oi.unit_price) AS total_revenue
    FROM orders o
    INNER JOIN order_items oi
        ON o.order_id = oi.order_id
    WHERE YEAR(o.order_date) = YEAR(CURDATE())
    GROUP BY MONTH(o.order_date)
)
SELECT
    m.month_number,
    MONTHNAME(
        STR_TO_DATE(m.month_number, '%m')
    ) AS month_name,
    COALESCE(ms.number_of_orders, 0) AS number_of_orders,
    COALESCE(ms.total_items_sold, 0) AS total_items_sold,
    COALESCE(ms.total_revenue, 0.00) AS total_revenue
FROM months m
LEFT JOIN monthly_sales ms
    ON m.month_number = ms.month_number
ORDER BY m.month_number;

/*
Expected Output:
Assuming the MySQL server's current year is 2026 and the sample orders
are dated in October 2026.

+--------------+------------+-----------------+-----------------+--------------+
| month_number | month_name | number_of_orders| total_items_sold| total_revenue|
+--------------+------------+-----------------+-----------------+--------------+
| 1            | January    | 0               | 0               | 0.00         |
| 2            | February   | 0               | 0               | 0.00         |
| 3            | March      | 0               | 0               | 0.00         |
| 4            | April      | 0               | 0               | 0.00         |
| 5            | May        | 0               | 0               | 0.00         |
| 6            | June       | 0               | 0               | 0.00         |
| 7            | July       | 0               | 0               | 0.00         |
| 8            | August     | 0               | 0               | 0.00         |
| 9            | September  | 0               | 0               | 0.00         |
| 10           | October    | 6               | 14              | 168.18       |
| 11           | November   | 0               | 0               | 0.00         |
| 12           | December   | 0               | 0               | 0.00         |
+--------------+------------+-----------------+-----------------+--------------+
(12 rows, based on the sample data)
*/