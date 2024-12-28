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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('cod')->unique();
            $table->string('title');
            $table->boolean('enabled')->default(true);
            $table->string('description',10000)->nullable();
            $table->float('price_monthly')->unsigned();
            $table->float('price_yearly')->unsigned();
            $table->integer('tournaments')->unsigned()->nullable();
            $table->integer('participants')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        $date = date('Y-m-d H:i:s');
        /**
         * @see https://aws.amazon.com/es/ses/pricing/
         * @see https://mailchimp.com/es/pricing/marketing/
         */
        $plans = [
            [
                'cod'           => 'unlimited', 
                'title'         => 'Unlimited', 
                'price_monthly' => 99999,  
                'price_yearly'  => 999990,  
                'description'   => null, 
                'enabled'       => 1, 
                'tournaments'  => null, 
                'participants' => null, 
                'created_at'    => $date, 
                'updated_at'    => $date
            ],
            [
                'cod'                 => 'free', 
                'title'               => 'Free', 
                'price_monthly'       => 0,  
                'price_yearly'        => 0,  
                'description'         => null, 
                'enabled'             => 1, 
                'tournaments'  => 10, 
                'participants' => 100, 
                'created_at'          => $date, 
                'updated_at'          => $date
            ],
            [  
                'cod'                 => 'starter', 
                'title'               => 'Starter', 
                'price_monthly'       => 99,  
                'price_yearly'        => 990,  
                'description'         => null, 
                'enabled'             => 1, 
                'tournaments'  => 100, 
                'participants' => 100 * 10000, 
                'created_at'          => $date, 
                'updated_at'          => $date
            ],
            [
                'cod'                 => 'small', 
                'title'               => 'Small', 
                'price_monthly'       => 199,  
                'price_yearly'        => 1990,  
                'description'         => null, 
                'enabled'             => 1, 
                'tournaments'  => 1000, 
                'participants' => 1000 * 10000,  
                'created_at'          => $date, 
                'updated_at'          => $date
            ],
        ];

        DB::table('plans')->insert($plans);
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
