# Contact Manager - MVC Refactoring (Web 2)

## 1. Requirements
- XAMPP (Apache, MySQL, PHP 8.0 or newer)
- PHP PDO MySQL extension enabled
- MySQL database named `contact_manager`

## 2. Installation
1. Extract the `contact_manager_mvc` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP Control Panel.
3. Open phpMyAdmin and check that the `contact_manager` database and `contacts` table exist.
4. The expected columns are: `id`, `name`, `email`, `phone`, `group_name`, `notes`, `created_at`, `updated_at`.
5. If the database/table does not exist, you may import `database.sql` in phpMyAdmin.
   Do not import it unnecessarily if your existing database already contains data.
6. Check `config/database.php` and update host, database name, username, or password if needed.
7. Open: http://localhost/contact_manager_mvc/public/index.php

## 3. Project structure
- `public/index.php`: single entry point and autoloader
- `public/.htaccess`: sets the directory index and disables directory listing
- `app/core/Database.php`: Singleton database connection
- `app/core/Router.php`: routes requests
- `app/models/Model.php`: abstract base model
- `app/models/ContactModel.php`: contact database operations using PDO prepared statements
- `app/controllers/ContactController.php`: request handling, validation and view rendering
- `app/views/`: HTML/PHP views
- `config/database.php`: database configuration

## 4. Routes
- `GET ?route=/` - list and search contacts
- `GET ?route=/create` - show create form
- `POST ?route=/store` - save new contact
- `GET ?route=/edit&id=1` - show edit form
- `POST ?route=/update` - update contact
- `POST ?route=/delete` - delete contact

## 5. Notes
This is a basic MVC implementation based on the contact fields discussed for this project. Compare it with the original Homework 10 requirements and source code before submission to ensure that every previously required feature is preserved.
