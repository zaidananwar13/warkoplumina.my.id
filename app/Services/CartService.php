<?php

namespace App\Services;

use App\Core\Session;
use App\Models\Product;

/**
 * Cart Service
 *
 * Manages the session-based shopping cart.
 * Cart structure: $_SESSION['cart'][$productId] = [id, name, price, qty]
 */
class CartService
{
    private const CART_KEY = 'cart';

    private Product $productModel;

    public function __construct(?Product $productModel = null)
    {
        $this->productModel = $productModel ?? new Product();
    }

    /**
     * Get all items in the cart.
     */
    public function getItems(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    /**
     * Add a product to the cart.
     *
     * @return array{status: string, message?: string, total_item?: int}
     */
    public function add(int $productId): array
    {
        $product = $this->productModel->findAvailable($productId);

        if (!$product) {
            return ['status' => 'error', 'message' => 'Produk tidak tersedia'];
        }

        $cart = $this->getItems();

        // Check stock limit
        if (isset($cart[$productId]) && $cart[$productId]['qty'] >= $product['stock']) {
            return ['status' => 'error', 'message' => 'Stok habis'];
        }

        // Add or increment
        if (!isset($cart[$productId])) {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => (int)$product['price'],
                'qty' => 1,
            ];
        } else {
            $cart[$productId]['qty']++;
        }

        Session::set(self::CART_KEY, $cart);

        return [
            'status' => 'success',
            'total_item' => $this->getTotalItems(),
        ];
    }

    /**
     * Increment quantity of a product in cart.
     */
    public function increment(int $productId): void
    {
        $cart = $this->getItems();

        if (isset($cart[$productId])) {
            $cart[$productId]['qty']++;
            Session::set(self::CART_KEY, $cart);
        }
    }

    /**
     * Decrement quantity of a product in cart.
     * Removes the item if quantity reaches 0.
     */
    public function decrement(int $productId): void
    {
        $cart = $this->getItems();

        if (isset($cart[$productId])) {
            $cart[$productId]['qty']--;

            if ($cart[$productId]['qty'] <= 0) {
                unset($cart[$productId]);
            }

            Session::set(self::CART_KEY, $cart);
        }
    }

    /**
     * Remove a product from the cart entirely.
     */
    public function remove(int $productId): void
    {
        $cart = $this->getItems();
        unset($cart[$productId]);
        Session::set(self::CART_KEY, $cart);
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        Session::forget(self::CART_KEY);
    }

    /**
     * Get total number of items in cart (sum of quantities).
     */
    public function getTotalItems(): int
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['qty'];
        }
        return $total;
    }

    /**
     * Calculate the cart total price.
     */
    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }

    /**
     * Check if the cart is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->getItems());
    }
}
