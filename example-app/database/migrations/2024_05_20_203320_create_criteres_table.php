<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('criteres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('preves_critere');
            $table->foreignId('champ_id')->constrained('champs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteres');
    }
};
