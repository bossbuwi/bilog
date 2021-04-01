<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configurations = [
            ['name' => 'offline', 'value' => 'N', 'description' => 'offline'],
            ['name' => 'devmode', 'value' => 'Y', 'description' => 'devmode'],
            ['name' => 'loglevel', 'value' => 'V', 'description' => 'loglevel'],
            ['name' => 'savepassword', 'value' => 'N', 'description' => 'savepassword'],
            ['name' => 'savelogs', 'value' => 'N', 'description' => 'savelogs']

        ];

        DB::table('configurations')->insert($configurations);
    }
}
