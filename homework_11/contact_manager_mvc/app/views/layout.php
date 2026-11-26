
<?php
$successMessage = $_SESSION['success'] ?? '';
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <h1>Contact Manager</h1>

            <nav>
                <a href="?route=%2F">Home</a>
                <a href="?route=%2Fcreate">Add Contact</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <?php if ($successMessage !== ''): ?>
            <div class="alert success">
                <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?= $content ?>

    </main>

    <footer>
        <p>Contact Manager - Web 2 Project</p>
    </footer>

</body>
</html>
