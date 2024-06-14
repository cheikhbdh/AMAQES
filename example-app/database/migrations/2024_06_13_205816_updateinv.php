<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evaluationinternes', function (Blueprint $table) {
            $table->unsignedBigInteger('idchamps')->nullable();
            $table->foreign('idchamps')->references('id')->on('champs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluationinterne', function (Blueprint $table) {
            //
        });
    }
};
