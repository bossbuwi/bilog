<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systems = [
            ['global_prefix' => 'OS', 'machine' => 'MNQ',
            'zone1_prefix' => 'O4', 'zone1_name' => 'Spain',
            'zone2_prefix' => 'O5', 'zone2_name' => 'Argentina',
            'login' => 'OSUSER01,OSUSER02,OSUSER03',
            'sysadmin' => 'Jhen Flores, MJ Mananghaya',
            'url' => 'https://mancswcbman0278:20025/fusionmidas/docs/index.jsp',],
            ['global_prefix' => 'YY', 'machine' => 'MNQ',
            'zone1_prefix' => 'Y5', 'zone1_name' => 'Spain',
            'zone2_prefix' => 'Y6', 'zone2_name' => 'Argentina',
            'login' => 'YYUSER01,YYUSER02,YYUSER03',
            'sysadmin' => 'Jhen Flores',
            'url' => 'https://mancswcbman0300:24244/fusionmidas/docs/index.jsp',],
        ];

        DB::table('systems')->insert($systems);
    }
}
