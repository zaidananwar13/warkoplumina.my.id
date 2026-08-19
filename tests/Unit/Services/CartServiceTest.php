<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Services\CartService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CartService.
 */
class CartServiceTest extends TestCase
{
    private CartService $cartService;

    protected function setUp(): void
    {
        // Start a clean session for each test
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];

        $productMock = $this->createMock(Product::class);
        $productMock->method('findAvailable')->willReturnCallback(function ($id) {
            if ($id === 1) {
                return ['id' => 1, 'name' => 'Kopi Susu', 'price' => 15000, 'stock' => 10];
            }
            if ($id === 2) {
                return ['id' => 2, 'name' => 'Nasi Goreng', 'price' => 25000, 'stock' => 5];
            }
            if ($id === 99) {
                return ['id' => 99, 'name' => 'Limited', 'price' => 5000, 'stock' => 1];
            }
            return null;
        });

        $this->cartService = new CartService($productMock);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function test_cart_starts_empty(): void
    {
        $this->assertTrue($this->cartService->isEmpty());
        $this->assertEquals(0, $this->cartService->getTotalItems());
        $this->assertEquals(0, $this->cartService->getTotal());
    }

    public function test_add_product_to_cart(): void
    {
        $result = $this->cartService->add(1);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(1, $result['total_item']);
        $this->assertFalse($this->cartService->isEmpty());
    }

    public function test_add_same_product_increments_quantity(): void
    {
        $this->cartService->add(1);
        $this->cartService->add(1);

        $items = $this->cartService->getItems();
        $this->assertEquals(2, $items[1]['qty']);
        $this->assertEquals(2, $this->cartService->getTotalItems());
    }

    public function test_add_nonexistent_product_returns_error(): void
    {
        $result = $this->cartService->add(999);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Produk tidak tersedia', $result['message']);
        $this->assertTrue($this->cartService->isEmpty());
    }

    public function test_add_respects_stock_limit(): void
    {
        // Product 99 has stock of 1
        $this->cartService->add(99);
        $result = $this->cartService->add(99);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Stok habis', $result['message']);
    }

    public function test_increment_increases_quantity(): void
    {
        $this->cartService->add(1);
        $this->cartService->increment(1);

        $items = $this->cartService->getItems();
        $this->assertEquals(2, $items[1]['qty']);
    }

    public function test_decrement_decreases_quantity(): void
    {
        $this->cartService->add(1);
        $this->cartService->add(1);
        $this->cartService->decrement(1);

        $items = $this->cartService->getItems();
        $this->assertEquals(1, $items[1]['qty']);
    }

    public function test_decrement_to_zero_removes_item(): void
    {
        $this->cartService->add(1);
        $this->cartService->decrement(1);

        $this->assertTrue($this->cartService->isEmpty());
    }

    public function test_remove_deletes_item(): void
    {
        $this->cartService->add(1);
        $this->cartService->add(2);
        $this->cartService->remove(1);

        $items = $this->cartService->getItems();
        $this->assertArrayNotHasKey(1, $items);
        $this->assertArrayHasKey(2, $items);
    }

    public function test_clear_empties_cart(): void
    {
        $this->cartService->add(1);
        $this->cartService->add(2);
        $this->cartService->clear();

        $this->assertTrue($this->cartService->isEmpty());
        $this->assertEquals(0, $this->cartService->getTotalItems());
    }

    public function test_get_total_calculates_correctly(): void
    {
        $this->cartService->add(1); // 15000
        $this->cartService->add(1); // 15000
        $this->cartService->add(2); // 25000

        // 15000 * 2 + 25000 * 1 = 55000
        $this->assertEquals(55000, $this->cartService->getTotal());
    }

    public function test_get_total_items_sums_quantities(): void
    {
        $this->cartService->add(1);
        $this->cartService->add(1);
        $this->cartService->add(2);

        $this->assertEquals(3, $this->cartService->getTotalItems());
    }
}
