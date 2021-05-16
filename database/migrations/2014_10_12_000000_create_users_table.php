<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verification_code')->nullable();
            $table->integer('level_id');
            $table->string('token')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert(array(
            [
                'name' => 'Admin',
                'email' => 'adminkratom@kratom.com',
                'email_verified_at' => now(),
                'password' => bcrypt('hailkratom'),
                'level_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user 1',
                'email' => 'userkratom1@kratom.com',
                'email_verified_at' => now(),
                'password' => bcrypt('user12'),
                'level_id' => 8,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user 2',
                'email' => 'userkratom2@kratom.com',
                'email_verified_at' => now(),
                'password' => bcrypt('user12'),
                'level_id' => 8,
                'created_at' => now(),
                'updated_at' => now()
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
        Schema::dropIfExists('users');
    }
}
