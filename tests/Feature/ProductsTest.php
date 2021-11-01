<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_homepage_contains_empty_products_table()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('No Products Found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $product = Product::create([
            'name' => 'Product 1',
            'price' => 99.99
        ]);
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertDontSee('No Products Found');
        $response->assertSee($product->name);

        $view_products = $response->viewData('products');
        $this->assertEquals($product->name, $view_products->first()->name);
    }
}
