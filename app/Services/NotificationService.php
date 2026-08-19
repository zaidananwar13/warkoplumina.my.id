<?php

namespace App\Services;

/**
 * Notification Service
 *
 * Stores and retrieves order status notifications using a JSON file.
 * Notifications are keyed by order ID so users can poll for their orders.
 */
class NotificationService
{
    private string $storagePath;

    public function __construct(?string $storagePath = null)
    {
        $this->storagePath = $storagePath ?? dirname(__DIR__, 2) . '/storage/notifications.json';
    }

    /**
     * Add a notification when order status changes.
     */
    public function notify(int $orderId, string $orderCode, string $newStatus): void
    {
        $notifications = $this->loadAll();

        $notifications[$orderId] = [
            'order_id' => $orderId,
            'order_code' => $orderCode,
            'status' => $newStatus,
            'message' => $this->statusMessage($orderCode, $newStatus),
            'time' => time(),
        ];

        // Keep only last 200 notifications to prevent file bloat
        if (count($notifications) > 200) {
            $notifications = array_slice($notifications, -200, null, true);
        }

        $this->saveAll($notifications);
    }

    /**
     * Get unread notifications for a list of order IDs.
     * Returns notifications newer than $since timestamp.
     */
    public function getForOrders(array $orderIds, int $since = 0): array
    {
        if (empty($orderIds)) {
            return [];
        }

        $notifications = $this->loadAll();
        $result = [];

        foreach ($orderIds as $id) {
            if (isset($notifications[$id]) && $notifications[$id]['time'] > $since) {
                $result[] = $notifications[$id];
            }
        }

        return $result;
    }

    /**
     * Generate a human-readable status message.
     */
    private function statusMessage(string $orderCode, string $status): string
    {
        return match ($status) {
            'processed' => "Pesanan {$orderCode} sedang diproses",
            'done' => "Pesanan {$orderCode} sudah selesai",
            'pending' => "Pesanan {$orderCode} menunggu konfirmasi",
            default => "Status pesanan {$orderCode} diperbarui",
        };
    }

    /**
     * Load all notifications from file.
     */
    private function loadAll(): array
    {
        if (!file_exists($this->storagePath)) {
            return [];
        }

        $content = file_get_contents($this->storagePath);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Save all notifications to file.
     */
    private function saveAll(array $notifications): void
    {
        $dir = dirname($this->storagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(
            $this->storagePath,
            json_encode($notifications, JSON_PRETTY_PRINT),
            LOCK_EX
        );
    }
}
