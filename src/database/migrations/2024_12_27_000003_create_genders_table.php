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
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('cod')->unique();
            $table->string('title');
        });
        DB::table('genders')->insert([
            [
                'cod' => 'F',
                'title' => 'Female',
            ],
            [
                'cod' => 'M',
                'title' => 'Male',
            ],
        ]);
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
