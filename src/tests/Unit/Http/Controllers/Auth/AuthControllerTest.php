<?php

namespace Tests\Unit\Http\Controllers\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use DB;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DepartmentTableSeeder']);
    }

    /** @test */
    public function register_異常系_department不備_失敗()
    {
        $data = [
            'name' => 'user_name',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'department' => 100,
        ];

        $response = $this->json('POST', route('register'), $data);

        $response
            ->assertStatus(422);
    }

    /** @test */
    public function register_異常系_email重複()
    {
        // 登録
        $data = [
            'name' => 'user_name',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'department' => 1,
        ];

        $response = $this->json('POST', route('register'), $data);
        $response
            ->assertStatus(200);

        // 再び登録
        $response = $this->json('POST', route('register'), $data);
        $response
            ->assertStatus(422);
    }

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

        $response
            ->assertStatus(200);

        // get content
        $content = json_decode($response->getContent());

        $user = DB::table('user')
            ->where('id', $content->id)
            ->first();

        $this->assertSame($user->id, $content->id);
        $this->assertSame($user->name, $content->name);
        $this->assertSame($user->depId, 1);
        $this->assertSame($user->email, $content->email);
    }
}
