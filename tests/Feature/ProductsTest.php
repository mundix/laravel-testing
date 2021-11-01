<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private function create_user($is_admin = 0)
    {
        $this->user =  User::factory()->make([
            'email' => $is_admin ? 'admin@admin.com' : 'user@user.com',
            'password' => bcrypt('password123'),
            'is_admin' => $is_admin
        ]);
    }

    /**
     * This test must be logged to access
    */
    public function test_homepage_contains_empty_products_table()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('No Products Found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $this->create_user();
        $product = Product::create([
            'name' => 'Product 1',
            'price' => 99.99
        ]);
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);

        $response->assertDontSee('No Products Found');
        $response->assertSee($product->name);

        $view_products = $response->viewData('products');
        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_pagination_products_table_doesnt_show_11th_record()
    {
        $this->create_user();
        $products = Product::factory()->count(11)->make();

        $response = $this->actingAs($this->user)->get('/');

        $response->assertDontSee($products->last()->name);
    }

    public function test_admin_can_see_product_create_button()
    {
        $this->create_user(1);

        $response = $this->actingAs($this->user)->get('products');
        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_admin_cannot_see_product_create_button()
    {
        $this->create_user(0);

        $response = $this->actingAs($this->user)->get('products');
        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }

    public function test_admin_can_see_products_create_page()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('products/create');
        $response->assertStatus(200);
    }

    public function test_nom_user_cannot_see_products_create_page()
    {
        $this->create_user(0);
        $response = $this->actingAs($this->user)->get('products/create');
        $response->assertStatus(403);
    }
}
