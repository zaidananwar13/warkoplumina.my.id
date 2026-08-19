<?php

namespace App\Models;

use App\Core\Model;

/**
 * Setting Model
 *
 * Key-value store for application settings.
 *
 * Table: settings
 * Columns: name (unique), value
 */
class Setting extends Model
{
    protected string $table = 'settings';
    protected string $primaryKey = 'name';

    /**
     * Get a setting value by key.
     */
    public function get(string $name, ?string $default = null): ?string
    {
        $stmt = $this->db()->prepare(
            "SELECT value FROM settings WHERE name = ?"
        );
        $stmt->execute([$name]);
        $result = $stmt->fetchColumn();

        return $result !== false ? $result : $default;
    }

    /**
     * Set a setting value (upsert).
     */
    public function set(string $name, string $value): bool
    {
        $stmt = $this->db()->prepare("
            INSERT INTO settings (name, value)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE value = ?
        ");
        return $stmt->execute([$name, $value, $value]);
    }

    /**
     * Get the WhatsApp admin number.
     */
    public function getWhatsAppNumber(): string
    {
        $number = $this->get('whatsapp_number');
        return $number ?: config('whatsapp')['admin_number'];
    }
}
