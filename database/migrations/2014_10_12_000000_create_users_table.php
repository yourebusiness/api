<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('companyId');
            $table->smallInteger('userId');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fName');
            $table->string('midName');
            $table->string('lName');
            $table->string('address');
            $table->enum('gender', ['M', 'F']);
            $table->dateTime('lastLogin');
            $table->smallInteger('updatedBy');
            $table->smallInteger('createdBy');
            $table->boolean('active');
            $table->boolean('activated');
            $table->tinyInteger('role');
            $table->boolean('trans');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
