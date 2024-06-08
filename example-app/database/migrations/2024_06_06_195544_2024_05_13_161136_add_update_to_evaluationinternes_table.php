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
        Schema::table('evaluationinterne', function (Blueprint $table) {
            $table->unsignedBigInteger('idfiliere')->default(0);
            $table->foreign('idfiliere')->references('id')->on('filières')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluationinternes', function (Blueprint $table) {
            //
        });
    }
};
