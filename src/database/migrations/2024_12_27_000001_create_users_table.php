<?php

use App\Models\Plan;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('plan_id')->references('id')->on('plans');

        });
        $date = date('Y-m-d H:i:s');
        DB::table('users')->insert([
            [
                'name'              => env('MASTER_USER_NAME','roquendo'),
                'plan_id'           => Plan::UNLIMITED,
                'password'          => Hash::make(env('MASTER_USER_PASSWORD','12345678')),
                'email'             => env('MASTER_USER_EMAIL', 'rodolfoquendo@gmail.com'),
                'email_verified_at' => $date,
                'created_at'        => $date, 
                'updated_at'        => $date
            ]
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
