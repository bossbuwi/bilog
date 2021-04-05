<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['username' => 'admin', 'password' => 'admin', 'admin' => true],
            ['username' => 'micmanan', 'password' => '', 'admin' => true]

        ];

        DB::table('users')->insert($users);
    }
}
