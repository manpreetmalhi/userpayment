<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Rashmi',
            'email' => 'rashmi@gmail.com',
            'password' => 'rashmi12',
            'password_confirmation' => 'rashmi12'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'msg' => 'User Registered Successfully',  
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'rashmi@gmail.com'
        ]);
    }

    /** @test */
    public function it_fails_to_register_user_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Rashmi',
            'email' => 'rash@example.com',
            'password' => 'rash',
            'password_confirmation' => 'rash'
        ]);

        $response->assertStatus(200)  
                 ->assertJsonStructure([
                     'errors' => ['password']
                 ]);
    }

    /** @test */
    public function it_logs_in_a_user_successfully()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('test123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@gmail.com',
            'password' => 'test123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'msg',
                     'token',
                     'token_type',
                     'expires_in'
                 ]);
    }

    /** @test */
    public function it_fails_to_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'rashmi13@gmail.com',
            'password' => Hash::make('rashmi13'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'rashmi13@gmail.com',
            'password' => 'rashmi12'
        ]);

        $response->assertStatus(200) 
                 ->assertJson([
                     'success' => false,
                     'msg' => 'Username or Password is incorrect.',
                 ]);
    }
}
