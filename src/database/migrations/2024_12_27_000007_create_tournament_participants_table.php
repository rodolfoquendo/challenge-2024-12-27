<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('participant_id');
            $table->boolean('entry_fee_paid');
            $table->boolean('winner');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['tournament_id', 'participant_id']);
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('participant_id')->references('id')->on('participants');
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
