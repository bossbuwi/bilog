<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_date_time = Carbon::now()->toDateTimeString();
        $events = [
            ['user' => 'micmanan', 'system' => 'OS', 'zone' => 'O4,O5',
                'type' => 'IC', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
                'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
                'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-03-31', 'end_date' => '2021-04-01',
                'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'MMATIGNA', 'system' => 'OS', 'zone' => 'O4,O5',
            'type' => 'IC', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
            'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
            'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-03-31', 'end_date' => '2021-04-03',
            'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'u724444', 'system' => 'OS', 'zone' => 'O4,O5',
                'type' => 'IC', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
                'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
                'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-04-01', 'end_date' => '2021-04-01',
                'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'pmillare', 'system' => 'OS', 'zone' => 'O4,O5',
            'type' => 'COB', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
            'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
            'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-04-01', 'end_date' => '2021-04-02',
            'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'quiambc1', 'system' => 'OS', 'zone' => 'O4,O5',
                'type' => 'IC-COB', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
                'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
                'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-04-02', 'end_date' => '2021-04-02',
                'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'zunigal', 'system' => 'OS', 'zone' => 'O4,O5',
            'type' => 'COB', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
            'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
            'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-04-02', 'end_date' => '2021-04-05',
            'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'naveloso', 'system' => 'OS', 'zone' => 'O4,O5',
                'type' => 'IC', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY',
                'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010',
                'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-04-08', 'end_date' => '2021-04-08',
                'created_at' => $current_date_time, 'updated_at' => $current_date_time],

            ['user' => 'ralagrio', 'system' => 'OS', 'zone' => 'O4,O5',
            'type' => 'COB', 'jira_case' => 'MD-12345', 'api_used' => 'OPAY,IPAY,CLIP,TRAD,DEAL,SIRS,CIRS',
            'compiled_sources' => 'FTC0630,FT0600,INPAYDD', 'feature_on' => 'CRE010,CAP209,CAP210,CSW220,CLE072,CSD158',
            'feature_off' => 'CAP205,CAP207', 'start_date' => '2021-04-08', 'end_date' => '2021-04-10',
            'created_at' => $current_date_time, 'updated_at' => $current_date_time],
        ];

        DB::table('events')->insert($events);
    }
}
