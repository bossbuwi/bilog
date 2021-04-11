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
            ['name' => 'offline', 'value' => 'N', 'description' => 'Flag that indicates if the REST server is offline.',
                'valid_values' => 'Y, N', 'default_value' => 'N'],
            ['name' => 'loglevel', 'value' => 'V', 'description' => 'Indicates the loglevel of the frontend provider',
                'valid_values' => 'N, V, I, W, E', 'default_value' => 'N'],
            ['name' => 'savepassword', 'value' => 'N', 'description' => 'Save the password of the users in the database.',
                'valid_values' => 'Y, N', 'default_value' => 'N'],
            ['name' => 'fulledit', 'value' => 'N', 'description' => 'Give non admin users freedom to edit all event details.',
                'valid_values' => 'Y, N', 'default_value' => 'N'],
            ['name' => 'allowdelete', 'value' => 'N', 'description' => 'Give non admin users freedom to delete events',
                'valid_values' => 'Y, N', 'default_value' => 'N']
        ];

        DB::table('configurations')->insert($configurations);
    }
}
