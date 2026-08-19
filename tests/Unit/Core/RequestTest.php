<?php

namespace Tests\Unit\Core;

use App\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Request class.
 */
class RequestTest extends TestCase
{
    public function test_method_returns_request_method(): void
    {
        $request = new Request([], [], [], ['REQUEST_METHOD' => 'POST']);

        $this->assertEquals('POST', $request->method());
    }

    public function test_method_defaults_to_get(): void
    {
        $request = new Request([], [], [], []);

        $this->assertEquals('GET', $request->method());
    }

    public function test_uri_returns_request_path(): void
    {
        $request = new Request([], [], [], ['REQUEST_URI' => '/cart?id=5']);

        $this->assertEquals('/cart', $request->uri());
    }

    public function test_query_returns_get_parameter(): void
    {
        $request = new Request(['page' => '2', 'sort' => 'name'], [], [], []);

        $this->assertEquals('2', $request->query('page'));
        $this->assertEquals('name', $request->query('sort'));
    }

    public function test_query_returns_default_when_missing(): void
    {
        $request = new Request([], [], [], []);

        $this->assertNull($request->query('missing'));
        $this->assertEquals('fallback', $request->query('missing', 'fallback'));
    }

    public function test_input_returns_post_parameter(): void
    {
        $request = new Request([], ['name' => 'Lumina', 'price' => '15000'], [], []);

        $this->assertEquals('Lumina', $request->input('name'));
        $this->assertEquals('15000', $request->input('price'));
    }

    public function test_input_returns_default_when_missing(): void
    {
        $request = new Request([], [], [], []);

        $this->assertEquals('-', $request->input('name', '-'));
    }

    public function test_is_post_checks_method(): void
    {
        $postRequest = new Request([], [], [], ['REQUEST_METHOD' => 'POST']);
        $getRequest = new Request([], [], [], ['REQUEST_METHOD' => 'GET']);

        $this->assertTrue($postRequest->isPost());
        $this->assertFalse($getRequest->isPost());
    }

    public function test_has_file_checks_upload(): void
    {
        $request = new Request([], [], [
            'image' => ['name' => 'photo.jpg', 'tmp_name' => '/tmp/xyz', 'error' => 0],
        ], []);

        $this->assertTrue($request->hasFile('image'));
        $this->assertFalse($request->hasFile('document'));
    }

    public function test_all_returns_all_post_data(): void
    {
        $post = ['name' => 'Test', 'value' => '123'];
        $request = new Request([], $post, [], []);

        $this->assertEquals($post, $request->all());
    }
}
