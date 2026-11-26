<div class="page-heading">
    <div>
        <h2>Contact List</h2>
        <p>Manage all your contacts here.</p>
    </div>
    <a href="?route=%2Fcreate" class="btn btn-primary">+ Add New Contact</a>
</div>

<form method="GET" action="" class="search-form">
    <input type="hidden" name="route" value="/">
    <input
        type="text"
        name="search"
        placeholder="Search by name, email or phone..."
        value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8') ?>"
    >
    <button type="submit" class="btn btn-primary">Search</button>
    <a href="?route=%2F" class="btn btn-secondary">Reset</a>
</form>

<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Group</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($contacts)): ?>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $contact['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($contact['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($contact['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($contact['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($contact['group_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($contact['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="actions">
                        <a
                            href="?route=%2Fedit&id=<?= urlencode((string) $contact['id']) ?>"
                            class="btn btn-edit"
                        >Edit</a>

                        <form
                            method="POST"
                            action="?route=%2Fdelete"
                            onsubmit="return confirm('Are you sure you want to delete this contact?');"
                        >
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $contact['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="empty-message">No contacts found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
