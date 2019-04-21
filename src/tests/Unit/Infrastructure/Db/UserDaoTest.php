<?php

namespace Tests\Unit\Infrastructure\Db;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Domain\User\User;
use App\Domain\User\Department;
use App\Infrastructure\Db\UserDao;
use Illuminate\Support\Collection;

class UserDaoTest extends TestCase
{
    use RefreshDatabase;

    const USER_TABLE_NAME = 'user';
    const DEP_TABLE_NAME = 'department';

    /** @var UserDao */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = app()->make(UserDao::class);

        $this->artisan('db:seed', ['--class' => 'UserTableSeeder']);
        $this->artisan('db:seed', ['--class' => 'DepartmentTableSeeder']);
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

        $queryResults = Db::table(self::USER_TABLE_NAME)
            ->select()
            ->join('department', self::USER_TABLE_NAME . '.depId', '=', self::DEP_TABLE_NAME . '.id')
            ->where(self::USER_TABLE_NAME . '.id', $userId)
            ->select([
                self::USER_TABLE_NAME . '.id as userId',
                self::USER_TABLE_NAME . '.name as userName',
                'email',
                'password',
                'role',
                'activation_token',
                self::DEP_TABLE_NAME . '.id as depId',
                self::DEP_TABLE_NAME . '.name as depName',
                self::DEP_TABLE_NAME . '.section_name as depSecName',
                self::DEP_TABLE_NAME . '.comment as depComment',
            ])
            ->get();
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
            UserDao::USER_TABLE_NAME,
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
                $queryResult->userId,
                $queryResult->userName,
                new Department(
                    $queryResult->depId,
                    $queryResult->depName,
                    $queryResult->depSecName,
                    $queryResult->depComment
                ),
                $queryResult->email,
                $queryResult->password,
                $queryResult->role,
                $queryResult->activation_token
            );
        }
        return $users;
    }
}
