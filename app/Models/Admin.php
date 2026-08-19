<?php

namespace App\Models;

use App\Core\Model;

/**
 * Admin Model
 *
 * Represents admin users who manage the system.
 *
 * Table: admins
 * Columns: id, username, password, role
 */
class Admin extends Model
{
    protected string $table = 'admins';

    public const ROLE_OWNER = 'owner';
    public const ROLE_KASIR = 'kasir';

    /**
     * Find an admin by username.
     */
    public function findByUsername(string $username): ?array
    {
        return $this->findBy('username', $username);
    }

    /**
     * Verify login credentials.
     *
     * @return array|null The admin record if credentials are valid, null otherwise.
     */
    public function authenticate(string $username, string $password): ?array
    {
        $admin = $this->findByUsername($username);

        if (!$admin) {
            return null;
        }

        if (!password_verify($password, $admin['password'])) {
            return null;
        }

        return $admin;
    }

    /**
     * Create or update an admin user.
     */
    public function save(array $data, ?int $id = null): int
    {
        $fields = [
            'username' => $data['username'],
            'role' => $data['role'] ?? self::ROLE_KASIR,
        ];

        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($id) {
            $this->update($id, $fields);
            return $id;
        }

        return $this->create($fields);
    }

    /**
     * Check if an admin has owner role.
     */
    public static function isOwner(string $role): bool
    {
        return $role === self::ROLE_OWNER;
    }
}
