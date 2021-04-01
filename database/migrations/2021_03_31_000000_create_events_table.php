<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('_id');
            $table->string('user');
            $table->string('system');
            $table->string('zone');
            $table->string('type');
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
        Schema::dropIfExists('events');
    }
}
