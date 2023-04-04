<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler_events', function (Blueprint $table) {
            $table->id();
            $table->date('date_event');
            $table->time('time_event_from');
            $table->time('time_event_to');
            $table->string('status');
            $table->integer('clinic_id');
            $table->integer('patient_id');
            $table->integer('cabinet_id');
            $table->integer('doctor_id');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('scheduler_events');
    }
}
