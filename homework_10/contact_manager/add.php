
<?php
/**
 * Contact Manager
 * File: add.php
 * Description: Add a new contact
 */

session_start();
require_once 'config.php';

$errors = [];

$name = '';
$email = '';
$phone = '';
$group_name = 'General';
$notes = '';

$allowedGroups = ['Family', 'Friends', 'Work', 'Other', 'General'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and sanitize input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $group_name = trim($_POST['group_name'] ?? 'General');
    $notes = trim($_POST['notes'] ?? '');

    // Validate name
    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Name must not exceed 100 characters.';
    }

    // Validate email
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        // Check whether email already exists
        $checkStmt = $pdo->prepare(
            "SELECT id FROM contacts WHERE email = :email"
        );

        $checkStmt->execute([':email' => $email]);

        if ($checkStmt->fetch()) {
            $errors[] = 'This email already exists.';
        }
    }

    // Validate group
    if (!in_array($group_name, $allowedGroups, true)) {
        $errors[] = 'Please select a valid group.';
    }

    // Insert contact if there are no errors
    if (empty($errors)) {

        $sql = "INSERT INTO contacts
                (name, email, phone, group_name, notes)
                VALUES
                (:name, :email, :phone, :group_name, :notes)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':group_name' => $group_name,
            ':notes' => $notes
        ]);

        // Store success message
        $_SESSION['flash_message'] = 'Contact added successfully!';
        $_SESSION['flash_type'] = 'success';

        // Redirect to homepage
        header('Location: index.php');
        exit;
    }
}

// Escape HTML output
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Contact</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header class="page-header">
        <div>
            <h1>Add New Contact</h1>
            <p>Enter the information of your new contact.</p>
        </div>

        <a href="index.php" class="btn btn-secondary">
            Back to Contacts
        </a>
    </header>

    <section class="content-card">

        <!-- Display errors -->
        <?php if (!empty($errors)): ?>

            <div class="alert error">
                <strong>Please fix the following errors:</strong>

                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php endif; ?>

        <!-- Add contact form -->
        <form method="POST" action="add.php" class="contact-form">

            <div class="form-group">
                <label for="name">Name *</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= e($name) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email *</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= e($email) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= e($phone) ?>"
                >
            </div>

            <div class="form-group">
                <label for="group_name">Group</label>

                <select id="group_name" name="group_name">

                    <?php foreach ($allowedGroups as $group): ?>

                        <option
                            value="<?= e($group) ?>"
                            <?= $group_name === $group ? 'selected' : '' ?>
                        >
                            <?= e($group) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="5"
                ><?= e($notes) ?></textarea>
            </div>

            <div class="form-actions">

                <button type="submit" class="btn btn-primary">
                    Save Contact
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </section>

</div>

</body>
</html>
