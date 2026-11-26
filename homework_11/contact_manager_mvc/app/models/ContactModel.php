
<?php

namespace App\Models;

use PDO;

class ContactModel extends Model
{
    /**
     * Get all contacts.
     * Supports searching by name, email or phone.
     */
    public function getAll(string $search = ''): array
    {
        if ($search !== '') {
            $sql = "SELECT * FROM contacts
                    WHERE name LIKE :search
                    OR email LIKE :search
                    OR phone LIKE :search
                    ORDER BY id DESC";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                'search' => '%' . $search . '%'
            ]);
        } else {
            $sql = "SELECT * FROM contacts ORDER BY id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    /**
     * Get one contact by ID.
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM contacts WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    /**
     * Create a new contact.
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO contacts
                (name, email, phone, group_name, notes)
                VALUES
                (:name, :email, :phone, :group_name, :notes)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'group_name' => $data['group_name'],
            'notes'      => $data['notes']
        ]);
    }

    /**
     * Update an existing contact.
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE contacts
                SET name = :name,
                    email = :email,
                    phone = :phone,
                    group_name = :group_name,
                    notes = :notes,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'group_name' => $data['group_name'],
            'notes'      => $data['notes'],
            'id'         => $id
        ]);
    }

    /**
     * Delete a contact.
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM contacts WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    /**
     * Check whether an email already exists.
     * Exclude current contact when updating.
     */
    public function emailExists(
        string $email,
        ?int $excludeId = null
    ): bool {
        $sql = "SELECT COUNT(*) FROM contacts
                WHERE email = :email";

        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }
}
