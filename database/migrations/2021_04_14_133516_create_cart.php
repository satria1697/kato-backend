<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer("user_id");
            $table->integer("goods_id");
            $table->string("buying");
            $table->integer("status");
            $table->timestamps();
        });

        DB::table('cart')->insert(array(
            [
                'user_id' => 2,
                'goods_id' => rand(1, 10),
                'buying' => rand(1,30),
                'status' => 4
            ],
            [
                'user_id' => 2,
                'goods_id' => rand(1, 10),
                'buying' => rand(1,30),
                'status' => 4
            ],
            [
                'user_id' => 2,
                'goods_id' => rand(1, 10),
                'buying' => rand(1,30),
                'status' => 4
            ],
            [
                'user_id' => 3,
                'goods_id' => rand(1, 10),
                'buying' => rand(1,30),
                'status' => 4
            ],
            [
                'user_id' => 3,
                'goods_id' => rand(1, 10),
                'buying' => rand(1,30),
                'status' => 4
            ]
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
