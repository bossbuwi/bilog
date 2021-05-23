<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Input Cycle', 'event_code' => 'IC', 'description' => 'Input Cycle',
            'restricted' => false, 'maintenance' => false, 'upgrade' => false],
            ['name' => 'Close of Business', 'event_code' => 'COB', 'description' => 'Close of Business',
            'restricted' => false, 'maintenance' => false, 'upgrade' => false],
            ['name' => 'IC - CoB', 'event_code' => 'IC-COB', 'description' => 'Input Cycle and Close of Business',
            'restricted' => false, 'maintenance' => false, 'upgrade' => false],
            ['name' => 'Maintenance', 'event_code' => 'MAINT', 'description' => 'System Maintenance',
            'restricted' => true, 'maintenance' => true, 'upgrade' => false],
            ['name' => 'System Upgrade', 'event_code' => 'SYSUP', 'description' => 'System Upgrade',
            'restricted' => true, 'maintenance' => false, 'upgrade' => true]
        ];

        DB::table('types')->insert($types);
    }
}
