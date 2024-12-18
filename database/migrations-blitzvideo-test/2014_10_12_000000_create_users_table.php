<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::connection('blitzvideo')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('fecha_de_nacimiento')->nullable();
            $table->boolean('bloqueado')->default(false);
            $table->boolean('premium')->default(false);
            $table->string('foto')->nullable();
            $table->rememberToken();
            $table->softDeletes(); 
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('users');
    }
}