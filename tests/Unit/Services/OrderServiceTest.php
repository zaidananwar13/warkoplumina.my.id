<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for OrderService.
 */
class OrderServiceTest extends TestCase
{
    public function test_calculate_total_sums_items(): void
    {
        $service = new OrderService(
            $this->createMock(Order::class),
            $this->createMock(OrderItem::class),
            $this->createMock(Product::class)
        );

        $items = [
            1 => ['id' => 1, 'name' => 'Kopi', 'price' => 15000, 'qty' => 2],
            2 => ['id' => 2, 'name' => 'Roti', 'price' => 10000, 'qty' => 3],
        ];

        $total = $service->calculateTotal($items);

        // 15000 * 2 + 10000 * 3 = 60000
        $this->assertEquals(60000, $total);
    }

    public function test_calculate_total_empty_cart_returns_zero(): void
    {
        $service = new OrderService(
            $this->createMock(Order::class),
            $this->createMock(OrderItem::class),
            $this->createMock(Product::class)
        );

        $this->assertEquals(0, $service->calculateTotal([]));
    }

    public function test_place_order_returns_error_for_empty_cart(): void
    {
        $service = new OrderService(
            $this->createMock(Order::class),
            $this->createMock(OrderItem::class),
            $this->createMock(Product::class)
        );

        $result = $service->placeOrder([], 'John', 'Kamar 1', 'Cash');

        $this->assertFalse($result['success']);
        $this->assertEquals('Keranjang kosong', $result['error']);
    }

    public function test_update_status_rejects_invalid_status(): void
    {
        $orderMock = $this->createMock(Order::class);
        $orderMock->expects($this->never())->method('updateStatus');

        $service = new OrderService(
            $orderMock,
            $this->createMock(OrderItem::class),
            $this->createMock(Product::class)
        );

        $result = $service->updateStatus(1, 'invalid_status');

        $this->assertFalse($result);
    }

    public function test_update_status_accepts_valid_statuses(): void
    {
        $orderMock = $this->createMock(Order::class);
        $orderMock->method('updateStatus')->willReturn(true);

        $service = new OrderService(
            $orderMock,
            $this->createMock(OrderItem::class),
            $this->createMock(Product::class)
        );

        $this->assertTrue($service->updateStatus(1, 'pending'));
        $this->assertTrue($service->updateStatus(1, 'processed'));
        $this->assertTrue($service->updateStatus(1, 'done'));
    }
}
