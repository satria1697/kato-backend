<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("company_id")->nullable();
            $table->string("id_card")->nullable();
            $table->integer("id_card_status")->default(3);
            $table->string("company_card")->nullable();
            $table->integer("company_card_status")->default(3);
            $table->timestamps();
        });

        DB::table('verification')->insert([
            'user_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification');
    }
}
