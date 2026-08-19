<?php

namespace App\Models;

use App\Core\Model;

/**
 * Product Model
 *
 * Represents menu items sold by the café.
 *
 * Table: products
 * Columns: id, name, category_id, price, stock, image, is_active
 */
class Product extends Model
{
    protected string $table = 'products';

    /**
     * Get active products for a given category with stock > 0.
     */
    public function getByCategoryId(int $categoryId): array
    {
        $stmt = $this->db()->prepare("
            SELECT * FROM products
            WHERE category_id = ?
              AND is_active = 1
              AND stock > 0
            ORDER BY name
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    /**
     * Find an active product by ID.
     */
    public function findActive(int $id): ?array
    {
        $stmt = $this->db()->prepare(
            "SELECT * FROM products WHERE id = ? AND is_active = 1"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find an active product with available stock.
     */
    public function findAvailable(int $id): ?array
    {
        $stmt = $this->db()->prepare(
            "SELECT * FROM products WHERE id = ? AND is_active = 1 AND stock > 0"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get all products with category names, optionally filtered.
     */
    public function getAllWithCategory(?int $categoryId = null, ?bool $isActive = null): array
    {
        $where = [];
        $params = [];

        if ($isActive !== null) {
            $where[] = 'p.is_active = ?';
            $params[] = $isActive ? 1 : 0;
        }

        if ($categoryId !== null) {
            $where[] = 'p.category_id = ?';
            $params[] = $categoryId;
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db()->prepare("
            SELECT p.*, c.name AS category
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            {$whereSQL}
            ORDER BY p.id DESC
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Create or update a product.
     */
    public function save(array $data, ?int $id = null): int
    {
        $fields = [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'image' => $data['image'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
        ];

        if ($id) {
            $this->update($id, $fields);
            return $id;
        }

        return $this->create($fields);
    }

    /**
     * Decrease stock for a product.
     */
    public function decreaseStock(int $id, int $quantity): bool
    {
        $stmt = $this->db()->prepare(
            "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?"
        );
        return $stmt->execute([$quantity, $id, $quantity]);
    }
}
