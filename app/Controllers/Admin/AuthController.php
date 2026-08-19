<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Admin;

/**
 * Admin Authentication Controller
 *
 * Handles admin login and logout.
 */
class AuthController extends Controller
{
    private Admin $adminModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
    }

    /**
     * Show the login form.
     */
    public function showLogin(Request $request): void
    {
        // Already logged in? Redirect to dashboard
        if (Session::get('admin_logged')) {
            $this->redirect('/admin/dashboard');
            return;
        }

        $this->view('admin.login', [
            'error' => '',
        ]);
    }

    /**
     * Process login credentials.
     */
    public function login(Request $request): void
    {
        $username = trim($request->input('username', ''));
        $password = $request->input('password', '');

        $admin = $this->adminModel->authenticate($username, $password);

        if (!$admin) {
            $this->view('admin.login', [
                'error' => 'Username atau password salah',
            ]);
            return;
        }

        // Set session
        Session::set('admin_logged', true);
        Session::set('admin_user', $admin['username']);
        Session::set('admin_role', $admin['role'] ?: 'owner');

        $this->redirect('/admin/dashboard');
    }

    /**
     * Logout and destroy session.
     */
    public function logout(Request $request): void
    {
        Session::destroy();
        $this->redirect('/admin/login');
    }
}
