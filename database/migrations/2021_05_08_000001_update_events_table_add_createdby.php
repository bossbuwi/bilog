<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;

class UpdateEventsTableAddCreatedby extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('created_by')->after('user');
        });

        $events = Event::all();

        foreach($events as $event) {
            if ($event->user !== NULL) {
                $event->created_by = $event->user;
                $event->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
}
