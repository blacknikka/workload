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
            'password_confirmation' => 'password',
        ];

        $response = $this->json('POST', route('register'), $data);

        // $user = DB::table('user')
        //     ->where();

        $response
            ->assertStatus(200);
            // ->assertJson(['name' => $user->name]);
    }
}
