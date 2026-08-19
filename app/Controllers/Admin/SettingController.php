<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Setting;

/**
 * Admin Setting Controller
 *
 * Manage application settings (owner only).
 */
class SettingController extends AdminController
{
    private Setting $settingModel;

    public function __construct()
    {
        $this->settingModel = new Setting();
    }

    /**
     * Display settings form.
     */
    public function index(Request $request): void
    {
        $this->requireOwner();

        $whatsapp = $this->settingModel->getWhatsAppNumber();

        $this->adminView('admin.settings', [
            'current_page' => 'settings',
            'whatsapp' => $whatsapp,
            'success' => false,
        ]);
    }

    /**
     * Save settings.
     */
    public function store(Request $request): void
    {
        $this->requireOwner();

        $whatsapp = preg_replace('/[^0-9]/', '', $request->input('whatsapp', ''));
        $this->settingModel->set('whatsapp_number', $whatsapp);

        $this->adminView('admin.settings', [
            'current_page' => 'settings',
            'whatsapp' => $whatsapp,
            'success' => true,
        ]);
    }
}
