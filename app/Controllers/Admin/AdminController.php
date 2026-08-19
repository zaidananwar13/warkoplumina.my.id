<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Response;
use App\Models\Admin;

/**
 * Base Admin Controller
 *
 * Provides authentication guard and role checking for all admin controllers.
 */
abstract class AdminController extends Controller
{
    /**
     * Check if the current user is authenticated as admin.
     * Redirects to login if not.
     */
    protected function authenticate(): void
    {
        if (!Session::get('admin_logged')) {
            Response::redirect('/admin/login');
            exit;
        }
    }

    /**
     * Require owner role. Aborts with 403 if not owner.
     */
    protected function requireOwner(): void
    {
        $this->authenticate();

        $role = Session::get('admin_role', 'kasir');

        if (!Admin::isOwner($role)) {
            Response::error('Akses ditolak', 403);
            exit;
        }
    }

    /**
     * Get the current admin role.
     */
    protected function getRole(): string
    {
        return Session::get('admin_role', 'kasir');
    }

    /**
     * Get the current admin username.
     */
    protected function getUsername(): string
    {
        return Session::get('admin_user', '');
    }

    /**
     * Render an admin view with common layout data.
     */
    protected function adminView(string $template, array $data = []): void
    {
        $data['admin_role'] = $this->getRole();
        $data['admin_user'] = $this->getUsername();
        $data['current_page'] = $data['current_page'] ?? '';

        $this->view($template, $data);
    }
}
