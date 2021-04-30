<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Configuration;

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
            ['name' => 'loglevel', 'value' => 'V', 'length' => '1',
                'description' => 'Indicates the frontend\'s logging level. N is None, V is Verbose, I is Info, W is Warning and E is Error.',
                'valid_values' => 'N,V,I,W,E', 'default_value' => 'N', 'last_modified_by' => 'admin'],
            ['name' => 'navtabsdesign', 'value' => 'M', 'length' => '1',
                'description' => 'Sets the design of the navigation tabs. C is for Classic tabs and M is for Modern tabs.',
                'valid_values' => 'C,M', 'default_value' => 'C', 'last_modified_by' => 'admin'],
            ['name' => 'displayauthor', 'value' => 'N', 'length' => '1',
                'description' => 'Toggles if the original developer/s are displayed in the frontend\'s about information.',
                'valid_values' => 'Y,N', 'default_value' => 'N', 'last_modified_by' => 'admin'],
            ['name' => 'openreports', 'value' => 'N', 'length' => '1',
                'description' => 'Gives all non admin users access to the reports tab.',
                'valid_values' => 'Y,N', 'default_value' => 'N', 'last_modified_by' => 'admin'],
            ['name' => 'primaryseed', 'value' => '9', 'length' => '1',
                'description' => 'The primary seed for encrypting the user\'s login details. Changing this will cause users to be logged out after a page reload.',
                'valid_values' => 'numeric', 'default_value' => '5', 'last_modified_by' => 'admin'],
            ['name' => 'keycode', 'value' => 'AkRlO62y', 'length' => '8',
                'description' => 'The key for solving the user\'s saved login code. Changing this will cause users to be logged out after a page reload.',
                'valid_values' => 'alphanumeric', 'default_value' => 'Az1By0Mj', 'last_modified_by' => 'admin'],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Configuration::truncate();
        DB::table('configurations')->insert($configurations);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
