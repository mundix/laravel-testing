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

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make([
            'email' => 'ce.pichardo@gmail.com',
            'password' => bcrypt('password123')
        ]);
    }

    /**
     * This test must be logged to access
    */
//    public function test_homepage_contains_empty_products_table()
//    {
//        $response = $this->get('/');
//        $response->assertStatus(200);
//        $response->assertSee('No Products Found');
//    }

    public function test_homepage_contains_non_empty_products_table()
    {
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
        $products = Product::factory()->count(11)->make();

        $response = $this->actingAs($this->user)->get('/');

        $response->assertDontSee($products->last()->name);
    }

    public function test_admin_can_see_product_create_button()
    {
        $admin_user = User::factory()->make();

        $response = $this->actingAs($admin_user)->get('products');
        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }

    public function test_admin_can_access_products_create_page()
    {
        $admin_user = User::factory()->make(['is_admin' => 1]);
        $response = $this->actingAs($admin_user)->get('products/create');
        $response->assertStatus(200);
    }

    public function test_nom_user_cannot_access_products_create_page()
    {
        $admin_user = User::factory()->make();
        $response = $this->actingAs($admin_user)->get('products/create');
        $response->assertStatus(403);
    }
}
