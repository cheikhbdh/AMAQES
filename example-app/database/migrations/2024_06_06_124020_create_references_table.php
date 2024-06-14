<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichier', function (Blueprint $table) {
            $table->id();
            $table->string('fichier');
            $table->unsignedBigInteger('idpreuve');
            $table->timestamps();
            $table->unsignedBigInteger('idfiliere')->default(0);
            $table->foreign('idfiliere')->references('id')->on('filières')->onDelete('cascade');
            $table->foreign('idpreuve')->references('id')->on('preuves')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichier');
    }
};
