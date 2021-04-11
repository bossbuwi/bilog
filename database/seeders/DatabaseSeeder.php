<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ConfigurationSeeder::class);
        $this->call(SystemSeeder::class);
        $this->call(RuleSeeder::class);
        $this->call(EventSeeder::class);
    }
}
