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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('cod');
            $table->string('title');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->decimal('entry_fee', total: 20, places: 2)->nullable();
            $table->unsignedBigInteger('max_winners')->default(1);
            $table->unsignedBigInteger('max_participants')->nullable();
            $table->unsignedBigInteger('participants')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
