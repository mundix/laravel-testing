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
        $this->user = User::factory()->make([
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

    public function test_store_product_exists_in_database()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->post('products', ['name' => 'New Product', 'price' => 99.99]);
        $response->assertRedirect('products');
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
        $product = Product::orderBy('id', 'desc')->first();
        $this->assertEquals('New Product', $product->name);
        $this->assertEquals(99.99, $product->price);
    }

    public function test_edit_product_form_contains_correct_name_and_price()
    {
        $this->create_user(1);
        $product = Product::create(['name' => 'New Product Test', 'price' => 99.99]);
        $response = $this->actingAs($this->user)->get("/products/{$product->id}/edit");
        $response->assertStatus(200);
//        $response->assertSee('value="' . $product->name . '"');
        $response->assertSee('value="' . $product->price . '"', false);
    }

    public function test_update_product_correct_validation_error()
    {
        $this->create_user(1);
        $product = Product::create(['name' => 'New Product Test', 'price' => 99.99]);

        $response = $this->actingAs($this->user)->put("/products/{$product->id}",
            ['name' => 'Test', 'price' => 99.99]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

}
