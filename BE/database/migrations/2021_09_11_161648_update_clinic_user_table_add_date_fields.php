<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClinicUserTableAddDateFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clinic_user', function (Blueprint $table) {
            $table->date('invite_date')->nullable();
            $table->date('last_visit_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clinic_user', function (Blueprint $table) {
            $table->dropColumn('invite_date');
            $table->dropColumn('last_visit_date');
        });
    }
}
