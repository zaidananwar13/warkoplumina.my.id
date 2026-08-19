<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Core\Database;

/**
 * Order Service
 *
 * Handles order creation, stock management, and order lifecycle.
 */
class OrderService
{
    private Order $orderModel;
    private OrderItem $orderItemModel;
    private Product $productModel;

    public function __construct(
        ?Order $orderModel = null,
        ?OrderItem $orderItemModel = null,
        ?Product $productModel = null
    ) {
        $this->orderModel = $orderModel ?? new Order();
        $this->orderItemModel = $orderItemModel ?? new OrderItem();
        $this->productModel = $productModel ?? new Product();
    }

    /**
     * Place a new order from cart items.
     *
     * @param array  $cartItems   The cart items [id => [id, name, price, qty]]
     * @param string $name        Customer name
     * @param string $room        Room number
     * @param string $payment     Payment method (Cash or QRIS)
     *
     * @return array{success: bool, order_id?: int, order_code?: string, error?: string}
     */
    public function placeOrder(
        array $cartItems,
        string $name,
        string $room,
        string $payment = 'Cash'
    ): array {
        if (empty($cartItems)) {
            return ['success' => false, 'error' => 'Keranjang kosong'];
        }

        $total = $this->calculateTotal($cartItems);
        $orderCode = Order::generateCode();

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Create the order
            $orderId = $this->orderModel->createOrder([
                'order_code' => $orderCode,
                'customer_name' => $name,
                'room_number' => $room,
                'total_price' => $total,
                'payment_method' => $payment,
            ]);

            // Create order items and decrease stock
            foreach ($cartItems as $item) {
                $this->orderItemModel->addItem($orderId, $item);
                $this->productModel->decreaseStock($item['id'], $item['qty']);
            }

            $db->commit();

            return [
                'success' => true,
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'total' => $total,
            ];
        } catch (\Exception $e) {
            $db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Calculate total price from cart items.
     */
    public function calculateTotal(array $cartItems): int
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }

    /**
     * Update order status.
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $validStatuses = [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSED,
            Order::STATUS_DONE,
        ];

        if (!in_array($status, $validStatuses, true)) {
            return false;
        }

        return $this->orderModel->updateStatus($orderId, $status);
    }

    /**
     * Get dashboard statistics.
     */
    public function getDashboardStats(): array
    {
        return [
            'total_products' => (new Product())->count(),
            'total_orders' => $this->orderModel->countTotal(),
            'today_orders' => $this->orderModel->countToday(),
            'today_revenue' => $this->orderModel->todayRevenue(),
            'sales_chart' => $this->orderModel->getSalesChart(7),
        ];
    }
}
