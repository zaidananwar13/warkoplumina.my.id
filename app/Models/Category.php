<?php

namespace App\Models;

use App\Core\Model;

/**
 * Category Model
 *
 * Represents product categories (e.g., Kopi, Makanan, Minuman).
 *
 * Table: categories
 * Columns: id, name, slug, status
 */
class Category extends Model
{
    protected string $table = 'categories';

    /**
     * Get all active categories ordered by name.
     */
    public function getActive(): array
    {
        return $this->db()->query(
            "SELECT * FROM categories WHERE status = 1 ORDER BY name"
        )->fetchAll();
    }

    /**
     * Find a category by its slug.
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    /**
     * Create or update a category.
     */
    public function save(array $data, ?int $id = null): int
    {
        $fields = [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?? '',
            'status' => $data['status'] ?? 1,
        ];

        if ($id) {
            $this->update($id, $fields);
            return $id;
        }

        return $this->create($fields);
    }
}
