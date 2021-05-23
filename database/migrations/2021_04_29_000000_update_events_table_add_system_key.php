<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\System;

class UpdateEventsTableAddSystemKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('system_id')->after('system');
        });

        $events = Event::all();

        foreach($events as $event) {
            if ($event->system !== NULL) {
                $system = System::where('global_prefix', $event->system)->first();
                $event->system_id = $system->id;
                $event->save();
            }
        }

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('system_id')->references('id')->on('systems');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->dropColumn('system_id');
        });
    }
}
