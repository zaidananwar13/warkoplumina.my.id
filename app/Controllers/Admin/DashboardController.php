<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Services\OrderService;

/**
 * Admin Dashboard Controller
 *
 * Shows summary statistics and sales chart.
 */
class DashboardController extends AdminController
{
    private OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    /**
     * Redirect /admin to /admin/dashboard.
     */
    public function redirect(Request $request): void
    {
        \App\Core\Response::redirect('/admin/dashboard');
    }

    /**
     * Display the dashboard with stats and chart data.
     */
    public function index(Request $request): void
    {
        $this->authenticate();

        $stats = $this->orderService->getDashboardStats();

        $this->adminView('admin.dashboard', [
            'current_page' => 'dashboard',
            'stats' => $stats,
        ]);
    }
}
