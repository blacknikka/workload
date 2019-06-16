<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\RegisterController;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DepartmentTableSeeder']);
    }

    // /** @test */
    // public function register_department不備_失敗()
    // {
    //     $data = [
    //         'name' => 'user_name',
    //         'email' => 'test@example.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //         'department' => 100,
    //     ];

    //     $response = $this->json('POST', route('register'), $data);

    //     $response
    //         ->assertStatus(422);
    // }

    /** @test */
    public function register_正常系()
    {
        $data = [
            'name' => 'user_name',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'department' => 1,
        ];

        $response = $this->json('POST', route('register'), $data);

        // $user = DB::table('user')
        //     ->where('id', );

        $response
            ->assertStatus(200);
            // ->assertJson(['name' => $user->name]);
    }
}
