<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_statistics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('date',7); // string because i intent to wrap the statistics per month so the date will be Y-m. This will be also for reporting and datalake
            $table->bigInteger('tournaments')->default(0);
            $table->bigInteger('participants')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * We do not reverse migrations with migrate command
     * for that create a reverse migration
     *
     * @return void
     */
    public function down(){}
};
