<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_正常系()
    {
        $data = [
            'name' => 'user_name',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', route('register'), $data);

        // $user = User::first();
        // $this->assertEquals($data['name'], $user->name);

        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]);
    }
}
