<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Order model (non-database logic).
 */
class OrderTest extends TestCase
{
    public function test_generate_code_has_correct_format(): void
    {
        $code = Order::generateCode();

        $this->assertStringStartsWith('LUM-', $code);
        $this->assertMatchesRegularExpression('/^LUM-\d{8}-\d{6}$/', $code);
    }

    public function test_generate_code_contains_current_date(): void
    {
        $code = Order::generateCode();
        $today = date('Ymd');

        $this->assertStringContainsString($today, $code);
    }

    public function test_status_constants_defined(): void
    {
        $this->assertEquals('pending', Order::STATUS_PENDING);
        $this->assertEquals('processed', Order::STATUS_PROCESSED);
        $this->assertEquals('done', Order::STATUS_DONE);
    }
}
