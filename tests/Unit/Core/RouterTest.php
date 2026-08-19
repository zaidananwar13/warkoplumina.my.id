<?php

namespace Tests\Unit\Core;

use App\Core\Router;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Router class.
 */
class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function test_can_register_get_route(): void
    {
        $this->router->get('/home', ['HomeController', 'index']);

        $result = $this->router->resolve('GET', '/home');

        $this->assertNotNull($result);
        $this->assertEquals(['HomeController', 'index'], $result['action']);
    }

    public function test_can_register_post_route(): void
    {
        $this->router->post('/submit', ['FormController', 'store']);

        $result = $this->router->resolve('POST', '/submit');

        $this->assertNotNull($result);
        $this->assertEquals(['FormController', 'store'], $result['action']);
    }

    public function test_returns_null_for_unregistered_route(): void
    {
        $this->router->get('/home', ['HomeController', 'index']);

        $result = $this->router->resolve('GET', '/unknown');

        $this->assertNull($result);
    }

    public function test_returns_null_for_wrong_method(): void
    {
        $this->router->get('/home', ['HomeController', 'index']);

        $result = $this->router->resolve('POST', '/home');

        $this->assertNull($result);
    }

    public function test_can_resolve_route_with_parameters(): void
    {
        $this->router->get('/category/{slug}', ['CategoryController', 'show']);

        $result = $this->router->resolve('GET', '/category/kopi');

        $this->assertNotNull($result);
        $this->assertEquals('kopi', $result['params']['slug']);
    }

    public function test_can_resolve_route_with_multiple_parameters(): void
    {
        $this->router->get('/admin/{section}/{id}', ['AdminController', 'show']);

        $result = $this->router->resolve('GET', '/admin/products/42');

        $this->assertNotNull($result);
        $this->assertEquals('products', $result['params']['section']);
        $this->assertEquals('42', $result['params']['id']);
    }

    public function test_group_prefix_applies_to_routes(): void
    {
        $this->router->group('/admin', function ($router) {
            $router->get('/dashboard', ['DashboardController', 'index']);
            $router->get('/products', ['ProductController', 'index']);
        });

        $result = $this->router->resolve('GET', '/admin/dashboard');
        $this->assertNotNull($result);
        $this->assertEquals(['DashboardController', 'index'], $result['action']);

        $result = $this->router->resolve('GET', '/admin/products');
        $this->assertNotNull($result);
        $this->assertEquals(['ProductController', 'index'], $result['action']);
    }

    public function test_nested_groups(): void
    {
        $this->router->group('/api', function ($router) {
            $router->group('/v1', function ($router) {
                $router->get('/users', ['UserController', 'index']);
            });
        });

        $result = $this->router->resolve('GET', '/api/v1/users');

        $this->assertNotNull($result);
        $this->assertEquals(['UserController', 'index'], $result['action']);
    }

    public function test_root_route_resolves(): void
    {
        $this->router->get('/', ['HomeController', 'index']);

        $result = $this->router->resolve('GET', '/');

        $this->assertNotNull($result);
        $this->assertEquals(['HomeController', 'index'], $result['action']);
    }

    public function test_ignores_query_string(): void
    {
        $this->router->get('/products', ['ProductController', 'index']);

        $result = $this->router->resolve('GET', '/products?status=active&cat=1');

        $this->assertNotNull($result);
        $this->assertEquals(['ProductController', 'index'], $result['action']);
    }
}
