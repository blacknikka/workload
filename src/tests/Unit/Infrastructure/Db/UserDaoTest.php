<?php

namespace Tests\Unit\Infrastructure\Db;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Domain\User\User;
use App\Infrastructure\Db\UserDao;
use Illuminate\Support\Collection;

class UserDaoTest extends TestCase
{
    use RefreshDatabase;

    const TABLE_NAME = 'user';

    /** @var UserDao */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = app()->make(UserDao::class);

        $this->artisan('db:seed', ['--class' => 'UserTableSeeder']);
    }

    /**
     * @test
     */
    public function find_正常系()
    {
        // ----------------------------------------
        // 2. アクション
        // ----------------------------------------
        $userId = 1;

        $queryResults = Db::table(self::TABLE_NAME)->select()->where('id', $userId)->get();
        $objectResults = $this->newFromQueryResults($queryResults);

        $sutResult = $this->sut->find($userId);


        // ----------------------------------------
        // 3. 検証
        // ----------------------------------------
        $this->assertEquals($sutResult, $objectResults[0]);
        $this->assertCount(1, $objectResults);
    }

    /** @test */
    public function find_存在しないユーザーを検索すると、nullが返却される()
    {
        // ----------------------------------------
        // 2. アクション
        // ----------------------------------------
        $user = $this->sut->find(999);

        // ----------------------------------------
        // 3. 検証
        // ----------------------------------------
        $this->assertNull($user);
        $this->assertDatabaseMissing(
            UserDao::TABLE_NAME,
            ['id' => 999]
        );
    }

    /**
     * @param array $queryResults
     * @return array
     */
    private function newFromQueryResults(Collection $queryResults) : array
    {
        $users = [];
        foreach ($queryResults as $queryResult) {
            $users[] =
            new User(
                $queryResult->id,
                $queryResult->email,
                $queryResult->password,
                $queryResult->role,
                $queryResult->activation_token
            );
        }
        return $users;
    }
}
