<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadTable extends Migration
{
    public function up()
    {
        Schema::connection('blitzvideo')->create('actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->text('detalles')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividad');
    }
}
