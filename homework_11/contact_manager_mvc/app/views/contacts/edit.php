<div class="page-heading">
    <div>
        <h2>Edit Contact</h2>
        <p>Update the contact information below.</p>
    </div>
</div>

<div class="form-container">
    <form method="POST" action="?route=%2Fupdate">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) $contact['id'], ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <?php if (!empty($errors['name'])): ?>
                <small class="error"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <?php if (!empty($errors['email'])): ?>
                <small class="error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="phone">Phone *</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <?php if (!empty($errors['phone'])): ?>
                <small class="error"><?= htmlspecialchars($errors['phone'], ENT_QUOTES, 'UTF-8') ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="group_name">Group</label>
            <input type="text" id="group_name" name="group_name" value="<?= htmlspecialchars($old['group_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="4"><?= htmlspecialchars($old['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Contact</button>
            <a href="?route=%2F" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
