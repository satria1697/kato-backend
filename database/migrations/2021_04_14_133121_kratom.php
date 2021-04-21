<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Kratom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('kratom', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string("name");
        //     $table->string("description");
        //     $table->string("price");
        //     $table->string("stock");
        //     $table->string("image")->nullable();
        //     $table->string("brief");
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kratom');
    }
}
