<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("name")->nullable();
            $table->string("company")->nullable();
            $table->string("position")->nullable();
            $table->timestamps();
        });

        DB::table('profile')->insert(array(
            [
                'user_id' => 1,
                'name' => 'admin'
            ],
            [
                'user_id' => 2,
                'name' => 'user 1'
            ],
            [
                'user_id' => 3,
                'name' => 'user 2'
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
        Schema::dropIfExists('profile');
    }
}
