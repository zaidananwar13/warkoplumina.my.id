<?php

namespace App\Services;

use App\Models\Setting;

/**
 * WhatsApp Service
 *
 * Formats order messages and generates WhatsApp redirect URLs.
 */
class WhatsAppService
{
    private Setting $settingModel;

    public function __construct(?Setting $settingModel = null)
    {
        $this->settingModel = $settingModel ?? new Setting();
    }

    /**
     * Build the WhatsApp redirect URL for an order.
     */
    public function buildOrderUrl(array $orderData): string
    {
        $message = $this->formatMessage($orderData);
        $phone = $this->getPhoneNumber();
        $encoded = rawurlencode($message);

        return "https://wa.me/{$phone}?text={$encoded}";
    }

    /**
     * Format the order message for WhatsApp.
     */
    public function formatMessage(array $orderData): string
    {
        $items = $this->formatItems($orderData['items']);
        $total = rupiah($orderData['total']);
        $name = $orderData['customer_name'] ?? '-';
        $room = $orderData['room_number'] ?? '-';
        $payment = $orderData['payment_method'] ?? 'Cash';

        $message = "\u{1F6D2} *Pesanan Warkop Lumina*\n\n";
        $message .= $items;
        $message .= "\n*Total: {$total}*\n\n";
        $message .= "\u{1F464} Nama: {$name}\n";
        $message .= "\u{1F4CD} Kamar: {$room}\n";
        $message .= "\u{1F4B3} Pembayaran: {$payment}";

        // Add change calculation for cash payments
        if ($payment === 'Cash' && !empty($orderData['amount_paid']) && $orderData['amount_paid'] > 0) {
            $paid = (int)$orderData['amount_paid'];
            $change = $paid - $orderData['total'];
            $message .= "\n\u{1F4B0} Uang Dibayar: " . rupiah($paid);
            $message .= "\n\u{1F4B8} Kembalian: " . rupiah($change);
        }

        $message .= "\n\n\u{1F64F} Terima kasih";

        return $message;
    }

    /**
     * Format cart items into a bullet list.
     */
    private function formatItems(array $items): string
    {
        $text = '';
        foreach ($items as $item) {
            $subtotal = $item['price'] * $item['qty'];
            $text .= "\u{2022} {$item['name']} x{$item['qty']} = " . rupiah($subtotal) . "\n";
        }
        return $text;
    }

    /**
     * Get the WhatsApp phone number from settings or config.
     */
    private function getPhoneNumber(): string
    {
        return $this->settingModel->getWhatsAppNumber();
    }
}
