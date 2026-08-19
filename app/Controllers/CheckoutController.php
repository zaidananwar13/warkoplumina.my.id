<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\WhatsAppService;

/**
 * Checkout Controller
 *
 * Handles the checkout form and order submission via WhatsApp.
 */
class CheckoutController extends Controller
{
    private CartService $cartService;
    private OrderService $orderService;
    private WhatsAppService $whatsAppService;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
        $this->whatsAppService = new WhatsAppService();
    }

    /**
     * Display the checkout form.
     */
    public function index(Request $request): void
    {
        if ($this->cartService->isEmpty()) {
            $this->redirect('/cart');
            return;
        }

        $total = $this->cartService->getTotal();

        $this->view('checkout.index', [
            'total' => $total,
        ]);
    }

    /**
     * Process the checkout: create order + redirect to WhatsApp.
     */
    public function process(Request $request): void
    {
        $cartItems = $this->cartService->getItems();

        if (empty($cartItems)) {
            Response::error('Keranjang kosong', 400);
            return;
        }

        $name = trim($request->input('nama', '-'));
        $room = trim($request->input('kamar', '-'));
        $payment = $request->input('pembayaran', 'Cash');
        $amountPaid = (int)$request->input('uang', 0);

        // Place the order in database
        $result = $this->orderService->placeOrder($cartItems, $name, $room, $payment);

        if (!$result['success']) {
            Response::error('Gagal menyimpan pesanan: ' . e($result['error']), 500);
            return;
        }

        // Build WhatsApp message
        $waUrl = $this->whatsAppService->buildOrderUrl([
            'items' => $cartItems,
            'total' => $result['total'],
            'customer_name' => $name,
            'room_number' => $room,
            'payment_method' => $payment,
            'amount_paid' => $amountPaid,
        ]);

        // Store order ID in session for history
        $myOrders = Session::get('my_orders', []);
        $myOrders[] = $result['order_id'];
        Session::set('my_orders', $myOrders);

        // Clear the cart
        $this->cartService->clear();

        // Redirect to WhatsApp
        $this->redirect($waUrl);
    }
}
