<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
//    public function test_homepage_contains_empty_products_table()
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//        $response->assertSee('No Products Found');
//    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $user =  User::factory()->make([
            'email' => 'ce.pichardo@gmail.com',
            'password' => bcrypt('password123')
        ]);

        $product = Product::create([
            'name' => 'Product 1',
            'price' => 99.99
        ]);
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        $response->assertDontSee('No Products Found');
        $response->assertSee($product->name);

        $view_products = $response->viewData('products');
        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_pagination_products_table_doesnt_show_11th_record()
    {
//        for ($i = 1; $i <= 11; $i++) {
//            $product = Product::create([
//                'name' => 'Product' . $i,
//                'price' => rand(10, 99)
//            ]);
//        }

        $user =  User::factory()->make([
            'email' => 'ce.pichardo@gmail.com',
            'password' => bcrypt('password123')
        ]);

        $products = Product::factory()->count(3)->make();

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee($products->last()->name);
    }
}
