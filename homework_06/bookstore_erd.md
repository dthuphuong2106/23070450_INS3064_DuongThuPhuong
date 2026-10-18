
# Bookstore Database ERD

## 1. Entities and Attributes

- authors
  - PK: author_id
  - author_name, biography, nationality

- publishers
  - PK: publisher_id
  - publisher_name, address, website, created_at

- categories
  - PK: category_id
  - category_name, description
  - FK: parent_category_id -> categories.category_id

- books
  - PK: book_id
  - title, ISBN, price, stock, publication_date, created_at
  - FK: publisher_id -> publishers.publisher_id

- book_authors
  - PK: (book_id, author_id)
  - FK: book_id -> books.book_id
  - FK: author_id -> authors.author_id

- book_categories
  - PK: (book_id, category_id)
  - FK: book_id -> books.book_id
  - FK: category_id -> categories.category_id

- customers
  - PK: customer_id
  - customer_name, email, password_hash, address, created_at

- orders
  - PK: order_id
  - FK: customer_id -> customers.customer_id
  - order_date, status, total, shipping_address

- order_items
  - PK: order_item_id
  - FK: order_id -> orders.order_id
  - FK: book_id -> books.book_id
  - quantity, unit_price, subtotal

- reviews
  - PK: review_id
  - FK: customer_id -> customers.customer_id
  - FK: book_id -> books.book_id
  - rating, comment, review_date

## 2. Relationships and Cardinality

- publishers 1:N books
- books M:N authors through book_authors
- books M:N categories through book_categories
- customers 1:N orders
- orders 1:N order_items
- books 1:N order_items
- customers 1:N reviews
- books 1:N reviews
- categories 1:N categories (parent-child relationship)
