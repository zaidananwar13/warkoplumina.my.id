<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Order;
use App\Models\OrderItem;

/**
 * History Controller
 *
 * Shows order history for the current user based on session-stored order IDs.
 */
class HistoryController extends Controller
{
    private Order $orderModel;
    private OrderItem $orderItemModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
    }

    /**
     * Display order history.
     */
    public function index(Request $request): void
    {
        $orderIds = Session::get('my_orders', []);
        $orders = [];

        foreach (array_reverse($orderIds) as $id) {
            $order = $this->orderModel->find($id);
            if ($order) {
                $order['items'] = $this->orderItemModel->getByOrderId($id);
                $orders[] = $order;
            }
        }

        $this->view('history.index', [
            'orders' => $orders,
        ]);
    }
}
