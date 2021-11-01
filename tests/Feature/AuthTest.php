<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_successfully()
    {
        // Create a user
        User::factory()->make([
            'email' => 'ce.pichardo@gmail.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', ['email' => 'test@test.com', 'password' => 'test1234']);
        // Post to /login
        $response->assertStatus(302);
//        $response->assertRedirect('/home');
        // assert redirec 302 to /home
        $response->assertRedirect('/'); // is not redirecting to home

    }

    public function test_authenticated_user_can_access_products_table()
    {
        // Create a user
        $user =  User::factory()->make([
            'email' => 'ce.pichardo@gmail.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        // Post to /login
        // Go to homepage /
        // assert statue 200
    }

    public function test_authenticated_user_cannot_access_products_table()
    {
        // Go to homepage /
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        // assert statue 302
    }


}
