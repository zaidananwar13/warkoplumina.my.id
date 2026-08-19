<?php

namespace App\Models;

use App\Core\Model;

/**
 * Order Model
 *
 * Represents customer orders placed through the system.
 *
 * Table: orders
 * Columns: id, order_code, customer_name, room_number, total_price,
 *          payment_method, status, created_at
 */
class Order extends Model
{
    protected string $table = 'orders';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_DONE = 'done';

    /**
     * Generate a unique order code.
     */
    public static function generateCode(): string
    {
        return 'LUM-' . date('Ymd-His');
    }

    /**
     * Create a new order.
     */
    public function createOrder(array $data): int
    {
        return $this->create([
            'order_code' => $data['order_code'],
            'customer_name' => $data['customer_name'],
            'room_number' => $data['room_number'],
            'total_price' => $data['total_price'],
            'payment_method' => $data['payment_method'] ?? 'Cash',
            'status' => self::STATUS_PENDING,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Update order status.
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Get all orders, optionally filtered to today only.
     */
    public function getOrders(bool $todayOnly = false): array
    {
        $where = $todayOnly ? "WHERE DATE(created_at) = CURDATE()" : '';

        return $this->db()->query("
            SELECT * FROM orders
            {$where}
            ORDER BY created_at DESC
        ")->fetchAll();
    }

    /**
     * Count total orders.
     */
    public function countTotal(): int
    {
        return $this->count();
    }

    /**
     * Count today's orders.
     */
    public function countToday(): int
    {
        return $this->count("DATE(created_at) = CURDATE()");
    }

    /**
     * Get today's total revenue.
     */
    public function todayRevenue(): int
    {
        $stmt = $this->db()->query("
            SELECT COALESCE(SUM(total_price), 0)
            FROM orders
            WHERE DATE(created_at) = CURDATE()
        ");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get sales data for the last N days (for chart).
     */
    public function getSalesChart(int $days = 7): array
    {
        $stmt = $this->db()->prepare("
            SELECT DATE(created_at) AS date,
                   SUM(total_price) AS total
            FROM orders
            GROUP BY DATE(created_at)
            ORDER BY date DESC
            LIMIT ?
        ");
        $stmt->execute([$days]);
        return array_reverse($stmt->fetchAll());
    }
}
