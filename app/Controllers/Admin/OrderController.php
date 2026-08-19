<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use App\Services\NotificationService;

/**
 * Admin Order Controller
 *
 * View and manage incoming orders.
 */
class OrderController extends AdminController
{
    private Order $orderModel;
    private OrderItem $orderItemModel;
    private OrderService $orderService;
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->orderService = new OrderService();
        $this->notificationService = new NotificationService();
    }

    /**
     * List all orders with optional today filter.
     */
    public function index(Request $request): void
    {
        $this->authenticate();

        $todayOnly = $request->query('today') !== null;
        $orders = $this->orderModel->getOrders($todayOnly);

        // Get detail for a specific order if requested
        $detail = null;
        $detailItems = [];
        if ($request->query('detail')) {
            $detailId = (int)$request->query('detail');
            $detail = $this->orderModel->find($detailId);
            $detailItems = $this->orderItemModel->getByOrderId($detailId);
        }

        $this->adminView('admin.orders', [
            'current_page' => 'orders',
            'orders' => $orders,
            'detail' => $detail,
            'detail_items' => $detailItems,
            'today_filter' => $todayOnly,
        ]);
    }

    /**
     * Update order status and send notification.
     */
    public function updateStatus(Request $request): void
    {
        $this->authenticate();

        $orderId = (int)$request->input('order_id', 0);
        $status = $request->input('status', '');

        if ($orderId && $status) {
            $this->orderService->updateStatus($orderId, $status);

            // Send notification to user
            $order = $this->orderModel->find($orderId);
            if ($order) {
                $this->notificationService->notify(
                    $orderId,
                    $order['order_code'],
                    $status
                );
            }
        }

        $this->redirect('/admin/orders');
    }
}
