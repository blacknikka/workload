<?php

namespace Tests\Unit\Http\Controllers\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Infrastructure\Db\UserDao;
use DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @var UserDao */
    private $userDao;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DepartmentTableSeeder']);
        $this->artisan('db:seed', ['--class' => 'UserTableSeeder']);

        $this->userDao = app()->make(UserDao::class);
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

    /** @test */
    public function authenticate_正常系()
    {
        // authenticate
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

        // authenticate
        $authenticate = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];
        $response = $this->json('POST', route('authenticate'), $authenticate);
        $response
            ->assertStatus(200);
    }

    /** @test */
    public function confirm_正常系()
    {
        $user = $this->userDao->find(1);
        $this->assertNotNull($user);

        // jwtを使って確認
        $token = JWTAuth::fromUser($user);
        $this->assertTrue($token !== '');

        JWTAuth::setToken($token);
        $headers = ['Accept' => 'application/json'];
        $headers['Authorization'] = 'Bearer ' . $token;

        $confirmResponse = $this->json(
            'POST',
            route('confirm'),
            [],
            $headers
        );
        $confirmResponse
            ->assertStatus(200);
        $confirmResult = json_decode($confirmResponse->getContent());
        $this->assertTrue($confirmResult->auth);
    }

    /** @test */
    public function confirm_異常系()
    {
        $user = $this->userDao->find(1);
        $this->assertNotNull($user);

        // jwtを使って確認
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $headers = ['Accept' => 'application/json'];

        // tokenを改造
        $headers['Authorization'] = 'Bearer ' . $token . 'aiueo';

        $confirmResponse = $this->json(
            'POST',
            route('confirm'),
            [],
            $headers
        );
        $confirmResponse
            ->assertStatus(401);
        $confirmResult = json_decode($confirmResponse->getContent());
        $this->assertFalse($confirmResult->auth);
    }

    /**
     * @test
     */
    public function getMyData_正常系()
    {
        $user = $this->userDao->find(1);
        $this->assertNotNull($user);

        // jwtを使って確認
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $headers = ['Accept' => 'application/json'];

        // tokenを設定
        $headers['Authorization'] = 'Bearer ' . $token;

        $confirmResponse = $this->get(
            route('getMyData'),
            $headers
        );
        $confirmResponse
            ->assertStatus(200);
        $confirmResult = json_decode($confirmResponse->getContent());

        $userResult = $confirmResult->user;
        $this->assertSame($user->getId(), $userResult->id);
        $this->assertSame($user->getName(), $userResult->name);
        $this->assertSame(
            $user->getDepartment()->getId(),
            $userResult->department->id
        );
        $this->assertSame($user->getEmail(), $userResult->email);
    }

    /**
     * @test
     */
    public function getMyData_異常系()
    {
        $user = $this->userDao->find(1);
        $this->assertNotNull($user);

        // jwtを使って確認
        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $headers = ['Accept' => 'application/json'];

        // tokenを改造
        $headers['Authorization'] = 'Bearer ' . $token. 'aiueo';

        $confirmResponse = $this->get(
            route('getMyData'),
            $headers
        );
        $confirmResponse
            ->assertStatus(401);
        $confirmResult = json_decode($confirmResponse->getContent());
        $this->assertSame($confirmResult->message, 'Auth error.');
    }
}
