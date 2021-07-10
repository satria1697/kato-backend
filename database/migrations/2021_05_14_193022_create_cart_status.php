<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCartStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_status', function (Blueprint $table) {
            $table->id();
            $table->string('description');
        });

        DB::table('cart_status')->insert(array(
            ['description' => 'Complete', 'description' => 'Selesai'],
            ['description' => 'Send', 'description' => 'Dikirim'],
            ['description' => 'Verify', 'description' => 'Terverifikasi'],
            ['description' => 'Pending', 'description' => 'Menunggu konfirmasi'],
            ['description' => 'Fail', 'description' => 'Gagal'],
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_status');
    }
}
