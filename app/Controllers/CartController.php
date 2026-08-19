<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Services\CartService;

/**
 * Cart Controller
 *
 * Manages the shopping cart: view, add, update quantities, remove items.
 */
class CartController extends Controller
{
    private CartService $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    /**
     * Display the cart page.
     */
    public function index(Request $request): void
    {
        $items = $this->cartService->getItems();
        $total = $this->cartService->getTotal();

        $this->view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart (GET request from storefront).
     */
    public function add(Request $request): void
    {
        $id = (int)$request->query('id', 0);

        if ($id) {
            $this->cartService->add($id);
        }

        // Return empty response for fetch() calls
        echo '';
    }

    /**
     * Add a product via AJAX (POST request with JSON response).
     */
    public function addAjax(Request $request): void
    {
        $id = (int)$request->input('product_id', 0);
        $result = $this->cartService->add($id);
        $this->json($result);
    }

    /**
     * Handle cart actions: plus, minus, remove.
     */
    public function action(Request $request): void
    {
        $id = (int)$request->query('id', 0);
        $action = $request->query('action', '');

        if (!$id) {
            $this->redirect('/cart');
            return;
        }

        match ($action) {
            'plus' => $this->cartService->increment($id),
            'minus' => $this->cartService->decrement($id),
            'remove' => $this->cartService->remove($id),
            default => null,
        };

        $this->redirect('/cart');
    }

    /**
     * Get cart item count as JSON (for floating cart badge).
     */
    public function count(Request $request): void
    {
        $this->json([
            'count' => $this->cartService->getTotalItems(),
        ]);
    }

    /**
     * Update cart via AJAX (POST with JSON response).
     */
    public function updateAjax(Request $request): void
    {
        $id = (int)$request->input('product_id', 0);
        $action = $request->input('action', '');

        if (!$id) {
            $this->json(['status' => 'error', 'message' => 'Invalid product']);
            return;
        }

        match ($action) {
            'plus' => $this->cartService->increment($id),
            'minus' => $this->cartService->decrement($id),
            'remove' => $this->cartService->remove($id),
            'clear' => $this->cartService->clear(),
            default => null,
        };

        $this->json([
            'status' => 'success',
            'total_item' => $this->cartService->getTotalItems(),
            'total_price' => $this->cartService->getTotal(),
        ]);
    }
}
