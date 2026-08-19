<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Admin model (non-database logic).
 */
class AdminTest extends TestCase
{
    public function test_is_owner_returns_true_for_owner_role(): void
    {
        $this->assertTrue(Admin::isOwner('owner'));
    }

    public function test_is_owner_returns_false_for_kasir_role(): void
    {
        $this->assertFalse(Admin::isOwner('kasir'));
    }

    public function test_is_owner_returns_false_for_empty_role(): void
    {
        $this->assertFalse(Admin::isOwner(''));
    }

    public function test_role_constants_exist(): void
    {
        $this->assertEquals('owner', Admin::ROLE_OWNER);
        $this->assertEquals('kasir', Admin::ROLE_KASIR);
    }
}
