<?php

use App\Models\Gender;
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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->string('cod');
            $table->string('title');
            $table->unique(['cod','gender_id']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('gender_id')->references('id')->on('genders');
        });
        $date = date('Y-m-d H:i:s');
        DB::table('skills')->insert([
            [
                'gender_id'  => Gender::F,
                'cod'        => 'reaction_time',
                'title'      => 'Reaction time',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'gender_id'  => Gender::M,
                'cod'        => 'speed',
                'title'      => 'Speed',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'gender_id'  => Gender::M,
                'cod'        => 'strength',
                'title'      => 'Strength',
                'created_at' => $date,
                'updated_at' => $date,
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
