<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $user1 = [
            'id' => 1,
            'name' => 'name1',
            'depId' => 1,
            'email' => 'test1@example.com',
            'password' => bcrypt('test1'),
            'role' => 1,
            'activation_token' => 'testtoken',
            'created_at' => $now,
            'updated_at' => $now,
        ];
        $user2 = [
            'id' => 2,
            'name' => 'name2',
            'depId' => 2,
            'email' => 'test2@example.com',
            'password' => bcrypt('test2'),
            'role' => 10,
            'activation_token' => 'testtoken2',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('user')->insert([$user1, $user2]);
    }
}
