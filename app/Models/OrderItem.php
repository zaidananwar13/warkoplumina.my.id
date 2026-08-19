<?php

namespace App\Models;

use App\Core\Model;

/**
 * OrderItem Model
 *
 * Represents individual line items within an order.
 *
 * Table: order_items
 * Columns: id, order_id, product_name, quantity, price, subtotal
 */
class OrderItem extends Model
{
    protected string $table = 'order_items';

    /**
     * Add an item to an order.
     */
    public function addItem(int $orderId, array $item): int
    {
        return $this->create([
            'order_id' => $orderId,
            'product_name' => $item['name'],
            'quantity' => $item['qty'],
            'price' => $item['price'],
            'subtotal' => $item['price'] * $item['qty'],
        ]);
    }

    /**
     * Get all items for a specific order.
     */
    public function getByOrderId(int $orderId): array
    {
        return $this->where('order_id', $orderId);
    }
}
