<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\Type;

class UpdateEventsTableAddTypeKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->after('type');
        });

        $events = Event::all();

        foreach($events as $event) {
            if ($event->type !== NULL) {
                $type = Type::where('event_code', $event->type)->first();
                $event->type_id = $type->id;
                $event->save();
            }
        }

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('types');
            $table->dropColumn('type');
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
            $table->dropColumn('type_id');
        });
    }
}
