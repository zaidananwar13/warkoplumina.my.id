<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for global helper functions.
 */
class FunctionsTest extends TestCase
{
    public function test_rupiah_formats_correctly(): void
    {
        $this->assertEquals('Rp15.000', rupiah(15000));
        $this->assertEquals('Rp1.500.000', rupiah(1500000));
        $this->assertEquals('Rp0', rupiah(0));
        $this->assertEquals('Rp500', rupiah(500));
    }

    public function test_e_escapes_html(): void
    {
        $this->assertEquals('&lt;script&gt;', e('<script>'));
        $this->assertEquals('&quot;hello&quot;', e('"hello"'));
        $this->assertEquals('Tom &amp; Jerry', e('Tom & Jerry'));
    }

    public function test_e_handles_null(): void
    {
        $this->assertEquals('', e(null));
    }

    public function test_base_url_generates_url(): void
    {
        putenv('APP_BASE_URL=http://localhost:8080/');

        $this->assertEquals('http://localhost:8080/', base_url());
        $this->assertEquals('http://localhost:8080/cart', base_url('cart'));
        $this->assertEquals('http://localhost:8080/admin/login', base_url('admin/login'));
    }

    public function test_asset_generates_asset_url(): void
    {
        putenv('APP_BASE_URL=http://localhost:8080/');

        $this->assertEquals('http://localhost:8080/assets/css/style.css', asset('css/style.css'));
        $this->assertEquals('http://localhost:8080/assets/js/cart.js', asset('js/cart.js'));
    }

    public function test_upload_url_generates_upload_url(): void
    {
        putenv('APP_BASE_URL=http://localhost:8080/');

        $this->assertEquals(
            'http://localhost:8080/uploads/products/image.png',
            upload_url('products/image.png')
        );
    }
}
