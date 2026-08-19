<?php

/**
 * Web Routes
 *
 * All application routes are defined here.
 * Routes map HTTP method + URI to controller actions.
 *
 * @var \App\Core\Router $router
 */

use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\HistoryController;
use App\Controllers\NotificationController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\SettingController;

// =============================================
// PUBLIC ROUTES
// =============================================

// Homepage
$router->get('/', [HomeController::class, 'index']);

// Category page
$router->get('/category/{slug}', [CategoryController::class, 'show']);

// Cart
$router->get('/cart', [CartController::class, 'index']);
$router->get('/cart/add', [CartController::class, 'add']);
$router->get('/cart/action', [CartController::class, 'action']);
$router->get('/cart/count', [CartController::class, 'count']);
$router->post('/cart/add', [CartController::class, 'addAjax']);
$router->post('/cart/update', [CartController::class, 'updateAjax']);

// Checkout
$router->get('/checkout', [CheckoutController::class, 'index']);
$router->post('/checkout/process', [CheckoutController::class, 'process']);

// Order History
$router->get('/history', [HistoryController::class, 'index']);

// Notifications (polling)
$router->get('/notifications/poll', [NotificationController::class, 'poll']);

// =============================================
// ADMIN ROUTES
// =============================================

$router->group('/admin', function ($router) {

    // Redirect /admin to /admin/dashboard
    $router->get('', [DashboardController::class, 'redirectToDashboard']);

    // Authentication
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/logout', [AuthController::class, 'logout']);

    // Dashboard
    $router->get('/dashboard', [DashboardController::class, 'index']);

    // Products
    $router->get('/products', [ProductController::class, 'index']);
    $router->post('/products', [ProductController::class, 'store']);

    // Categories
    $router->get('/categories', [AdminCategoryController::class, 'index']);
    $router->post('/categories', [AdminCategoryController::class, 'store']);
    $router->get('/categories/delete', [AdminCategoryController::class, 'delete']);

    // Orders
    $router->get('/orders', [OrderController::class, 'index']);
    $router->post('/orders/status', [OrderController::class, 'updateStatus']);

    // Settings
    $router->get('/settings', [SettingController::class, 'index']);
    $router->post('/settings', [SettingController::class, 'store']);
});
