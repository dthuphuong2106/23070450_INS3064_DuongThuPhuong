<?php

namespace App\Models;

class ContactModel extends Model
{
    public function getAll(string $search = ''): array
    {
        if ($search !== '') {
            $sql = "SELECT * FROM contacts
                    WHERE name LIKE :name
                       OR email LIKE :email
                       OR phone LIKE :phone
                    ORDER BY id DESC";

            $stmt = $this->db->prepare($sql);
            $keyword = '%' . $search . '%';

            $stmt->execute([
                'name' => $keyword,
                'email' => $keyword,
                'phone' => $keyword
            ]);
        } else {
            $stmt = $this->db->prepare(
                'SELECT * FROM contacts ORDER BY id DESC'
            );
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM contacts WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO contacts
                (name, email, phone, group_name, notes)
                VALUES
                (:name, :email, :phone, :group_name, :notes)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'group_name' => $data['group_name'],
            'notes' => $data['notes']
        ]);
    }

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
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'group_name' => $data['group_name'],
            'notes' => $data['notes'],
            'id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM contacts WHERE id = :id'
        );

        return $stmt->execute(['id' => $id]);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM contacts WHERE email = :email';
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }
}
