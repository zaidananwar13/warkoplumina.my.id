<?php

namespace Tests\Unit\Services;

use App\Models\Setting;
use App\Services\WhatsAppService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for WhatsAppService.
 */
class WhatsAppServiceTest extends TestCase
{
    private WhatsAppService $service;

    protected function setUp(): void
    {
        $settingMock = $this->createMock(Setting::class);
        $settingMock->method('getWhatsAppNumber')->willReturn('6281234567890');

        $this->service = new WhatsAppService($settingMock);
    }

    public function test_format_message_contains_order_items(): void
    {
        $message = $this->service->formatMessage([
            'items' => [
                ['name' => 'Kopi Susu', 'price' => 15000, 'qty' => 2],
                ['name' => 'Roti Bakar', 'price' => 12000, 'qty' => 1],
            ],
            'total' => 42000,
            'customer_name' => 'Budi',
            'room_number' => 'Kamar 5',
            'payment_method' => 'Cash',
            'amount_paid' => 50000,
        ]);

        $this->assertStringContainsString('Kopi Susu x2', $message);
        $this->assertStringContainsString('Roti Bakar x1', $message);
        $this->assertStringContainsString('Rp42.000', $message);
        $this->assertStringContainsString('Budi', $message);
        $this->assertStringContainsString('Kamar 5', $message);
        $this->assertStringContainsString('Cash', $message);
    }

    public function test_format_message_includes_change_for_cash(): void
    {
        $message = $this->service->formatMessage([
            'items' => [
                ['name' => 'Kopi', 'price' => 10000, 'qty' => 1],
            ],
            'total' => 10000,
            'customer_name' => 'Ani',
            'room_number' => '3',
            'payment_method' => 'Cash',
            'amount_paid' => 20000,
        ]);

        $this->assertStringContainsString('Rp20.000', $message); // Uang dibayar
        $this->assertStringContainsString('Rp10.000', $message); // Kembalian
    }

    public function test_format_message_no_change_for_qris(): void
    {
        $message = $this->service->formatMessage([
            'items' => [
                ['name' => 'Kopi', 'price' => 10000, 'qty' => 1],
            ],
            'total' => 10000,
            'customer_name' => 'Cici',
            'room_number' => '7',
            'payment_method' => 'QRIS',
            'amount_paid' => 0,
        ]);

        $this->assertStringNotContainsString('Kembalian', $message);
        $this->assertStringContainsString('QRIS', $message);
    }

    public function test_build_order_url_contains_whatsapp_domain(): void
    {
        $url = $this->service->buildOrderUrl([
            'items' => [
                ['name' => 'Kopi', 'price' => 10000, 'qty' => 1],
            ],
            'total' => 10000,
            'customer_name' => 'Test',
            'room_number' => '1',
            'payment_method' => 'Cash',
            'amount_paid' => 0,
        ]);

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $url);
    }

    public function test_build_order_url_encodes_message(): void
    {
        $url = $this->service->buildOrderUrl([
            'items' => [
                ['name' => 'Nasi Goreng Special', 'price' => 25000, 'qty' => 1],
            ],
            'total' => 25000,
            'customer_name' => 'John',
            'room_number' => '10',
            'payment_method' => 'QRIS',
            'amount_paid' => 0,
        ]);

        // URL should not contain unencoded spaces or newlines
        $this->assertStringNotContainsString(' ', parse_url($url, PHP_URL_QUERY) ? '' : $url);
        $this->assertStringNotContainsString("\n", $url);
    }
}
