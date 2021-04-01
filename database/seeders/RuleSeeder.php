<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rules = [
            ['statement' => 'Please contact the system owners for the system password.'],
            ['statement' => 'Please fill up the information on the log correctly.'],
            ['statement' => 'Please delete compiled sources on xxINVLIB after using the system.'],
            ['statement' => 'Please return features that were turned ON or OFF after use.'],
            ['statement' => 'If possible, please reverse inserted transactions after testing.'],
            ['statement' => 'Before reserving for a COB, please inform the system owners beforehand.']
        ];

        DB::table('rules')->insert($rules);
    }
}
