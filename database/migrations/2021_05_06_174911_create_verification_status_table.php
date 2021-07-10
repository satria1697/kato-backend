<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVerificationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_status', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->string("description_id");
            $table->string("color");
        });

        DB::table('verification_status')->insert(array(
            ['description' => 'Verified','description_id' => 'Diverfikasi', 'color' => '#00cf18'],
            ['description' => 'Pending','description_id' => 'Menunggu konfirmasi', 'color' => '#c6c90'],
            ['description' => 'Empty','description_id' => 'Kosong', 'color' => '#cfcfcf'],
            ['description' => 'Rejected','description_id' => 'Ditolak', 'color' => '#ff4d4d'],
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_status');
    }
}
