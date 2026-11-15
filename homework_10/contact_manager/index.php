
<?php
/**
 * Contact Manager
 * File: index.php
 * Description: Display and search contacts
 */

session_start();

require_once 'config.php';

// Get search keyword
$search = trim($_GET['search'] ?? '');

// Count all contacts
$countStmt = $pdo->query("SELECT COUNT(*) FROM contacts");
$totalContacts = $countStmt->fetchColumn();

// Get contacts
if ($search !== '') {
    $sql = "SELECT * FROM contacts
            WHERE name LIKE :search_name
            OR email LIKE :search_email
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);

    $keyword = "%{$search}%";

    $stmt->execute([
        ':search_name' => $keyword,
        ':search_email' => $keyword
    ]);

    $contacts = $stmt->fetchAll();

} else {
    $sql = "SELECT * FROM contacts ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $contacts = $stmt->fetchAll();
}

// Get flash message
$flashMessage = $_SESSION['flash_message'] ?? null;
$flashType = $_SESSION['flash_type'] ?? 'success';

// Clear flash message after displaying
unset($_SESSION['flash_message']);
unset($_SESSION['flash_type']);

// Escape output to prevent XSS
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header class="page-header">
        <div>
            <h1>Contact Manager</h1>
            <p>Manage your contacts in one place.</p>
        </div>

        <a href="add.php" class="btn btn-primary">
            + Add New Contact
        </a>
    </header>

    <!-- Total contacts -->
    <div class="summary">
        <h2>Total Contacts</h2>
        <p><?= e($totalContacts) ?></p>
    </div>

    <!-- Flash message -->
    <?php if ($flashMessage): ?>
        <div class="alert <?= e($flashType) ?>">
            <?= e($flashMessage) ?>
        </div>
    <?php endif; ?>

    <!-- Search form -->
    <section class="content-card">

        <form method="GET" action="index.php" class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search by name or email..."
                value="<?= e($search) ?>"
            >

            <button type="submit" class="btn btn-primary">
                Search
            </button>

            <a href="index.php" class="btn btn-secondary">
                Reset
            </a>

        </form>

        <!-- Contacts table -->
        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Group</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($contacts) > 0): ?>

                        <?php foreach ($contacts as $contact): ?>

                            <tr>
                                <td><?= e($contact['name']) ?></td>

                                <td><?= e($contact['email']) ?></td>

                                <td><?= e($contact['phone']) ?></td>

                                <td>
                                    <span class="group-badge">
                                        <?= e($contact['group_name']) ?>
                                    </span>
                                </td>

                                <td><?= e($contact['created_at']) ?></td>

                                <td class="actions">

                                    <a
                                        href="edit.php?id=<?= e($contact['id']) ?>"
                                        class="btn btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete.php?id=<?= e($contact['id']) ?>"
                                        class="btn btn-delete"
                                    >
                                        Delete
                                    </a>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="empty-message">
                                No contacts found.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <p class="result-count">
            Showing <?= count($contacts) ?> contact(s)
        </p>

    </section>

</div>

</body>
</html>
