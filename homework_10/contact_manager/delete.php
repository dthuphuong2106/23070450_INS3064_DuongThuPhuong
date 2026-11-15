
<?php
/**
 * Contact Manager
 * File: delete.php
 * Description: Confirm and delete a contact
 */

session_start();
require_once 'config.php';

// Get contact ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Validate ID
if (!$id || $id <= 0) {
    die('Invalid contact ID. <a href="index.php">Go back</a>');
}

// Find contact
$stmt = $pdo->prepare(
    "SELECT * FROM contacts WHERE id = :id"
);

$stmt->execute([':id' => $id]);

$contact = $stmt->fetch();

// Check whether contact exists
if (!$contact) {
    die('Contact not found. <a href="index.php">Go back</a>');
}

// Process delete confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Delete contact using POST only
    $deleteStmt = $pdo->prepare(
        "DELETE FROM contacts WHERE id = :id"
    );

    $deleteStmt->execute([':id' => $id]);

    // Store success message
    $_SESSION['flash_message'] = 'Contact deleted successfully!';
    $_SESSION['flash_type'] = 'success';

    // Redirect to homepage
    header('Location: index.php');
    exit;
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
    <title>Delete Contact</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header class="page-header">
        <div>
            <h1>Delete Contact</h1>
            <p>Confirm before deleting this contact.</p>
        </div>

        <a href="index.php" class="btn btn-secondary">
            Back to Contacts
        </a>
    </header>

    <section class="content-card">

        <div class="alert error">
            <strong>Warning!</strong>
            Are you sure you want to delete this contact?
            This action cannot be undone.
        </div>

        <div class="contact-details">

            <p>
                <strong>Name:</strong>
                <?= e($contact['name']) ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?= e($contact['email']) ?>
            </p>

            <p>
                <strong>Phone:</strong>
                <?= e($contact['phone']) ?>
            </p>

            <p>
                <strong>Group:</strong>
                <?= e($contact['group_name']) ?>
            </p>

            <p>
                <strong>Notes:</strong>
                <?= e($contact['notes']) ?>
            </p>

        </div>

        <!-- POST confirmation form -->
        <form method="POST"
              action="delete.php?id=<?= e($id) ?>"
              class="form-actions">

            <button type="submit" class="btn btn-delete">
                Confirm Delete
            </button>

            <a href="index.php" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </section>

</div>

</body>
</html>
