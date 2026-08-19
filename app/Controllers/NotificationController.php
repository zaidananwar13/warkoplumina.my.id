<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\NotificationService;

/**
 * Notification Controller
 *
 * Provides a polling endpoint for users to check order status updates.
 */
class NotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * Poll for new notifications (JSON).
     * Client sends ?since=<timestamp> to get only new ones.
     */
    public function poll(Request $request): void
    {
        $orderIds = Session::get('my_orders', []);
        $since = (int)$request->query('since', 0);

        $notifications = $this->notificationService->getForOrders($orderIds, $since);

        $this->json([
            'notifications' => $notifications,
            'server_time' => time(),
        ]);
    }
}
