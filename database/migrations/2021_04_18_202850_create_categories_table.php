<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_id');
            $table->boolean('show');
            $table->timestamps();
        });

        DB::table('categories')->insert(array(
            ['name' => 'Table','name_id' => 'Meja', 'show' => 1],
            ['name' => 'Chair','name_id' => 'Kursi', 'show' => 1],
            ['name' => 'Dumdum', 'name_id' => 'Dumdum','show' => 1],
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
