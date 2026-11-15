
<?php
/**
 * Contact Manager
 * File: edit.php
 * Description: Edit an existing contact
 */

session_start();
require_once 'config.php';

$errors = [];

$allowedGroups = ['Family', 'Friends', 'Work', 'Other', 'General'];

// Get contact ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Validate ID
if (!$id || $id <= 0) {
    die('Invalid contact ID. <a href="index.php">Go back</a>');
}

// Find existing contact
$stmt = $pdo->prepare(
    "SELECT * FROM contacts WHERE id = :id"
);
$stmt->execute([':id' => $id]);

$contact = $stmt->fetch();

// Check if contact exists
if (!$contact) {
    die('Contact not found. <a href="index.php">Go back</a>');
}

// Load existing values
$name = $contact['name'];
$email = $contact['email'];
$phone = $contact['phone'];
$group_name = $contact['group_name'];
$notes = $contact['notes'];

// Process update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

        // Check duplicate email, excluding current contact
        $checkStmt = $pdo->prepare(
            "SELECT id FROM contacts
             WHERE email = :email AND id != :id"
        );

        $checkStmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);

        if ($checkStmt->fetch()) {
            $errors[] = 'This email already belongs to another contact.';
        }
    }

    // Validate group
    if (!in_array($group_name, $allowedGroups, true)) {
        $errors[] = 'Please select a valid group.';
    }

    // Update database
    if (empty($errors)) {

        $sql = "UPDATE contacts
                SET name = :name,
                    email = :email,
                    phone = :phone,
                    group_name = :group_name,
                    notes = :notes
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':group_name' => $group_name,
            ':notes' => $notes,
            ':id' => $id
        ]);

        $_SESSION['flash_message'] = 'Contact updated successfully!';
        $_SESSION['flash_type'] = 'success';

        header('Location: index.php');
        exit;
    }
}

// Escape output
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header class="page-header">
        <div>
            <h1>Edit Contact</h1>
            <p>Update the contact information below.</p>
        </div>

        <a href="index.php" class="btn btn-secondary">
            Back to Contacts
        </a>
    </header>

    <section class="content-card">

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

        <form method="POST"
              action="edit.php?id=<?= e($id) ?>"
              class="contact-form">

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
                    Save Changes
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
