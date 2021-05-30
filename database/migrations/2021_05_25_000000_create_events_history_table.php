<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventshistory', function (Blueprint $table) {
            $table->id('_id');
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('_id')->on('events');
            $table->string('status');
            $table->string('created_by');
            $table->string('last_modified_by');
            $table->unsignedBigInteger('system_id');
            $table->foreign('system_id')->references('id')->on('systems');
            $table->string('zone');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->string('jira_case')->nullable();
            $table->string('api_used')->nullable();
            $table->string('compiled_sources')->nullable();
            $table->string('feature_on')->nullable();
            $table->string('feature_off')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventshistory');
    }
}
