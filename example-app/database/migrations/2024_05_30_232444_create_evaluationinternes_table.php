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
        Schema::create('evaluationinternes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcritere');
            $table->unsignedBigInteger('idpreuve');
            $table->integer('score')->comment('0, 1, or -1');
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->foreign('idcritere')->references('id')->on('criteres')->onDelete('cascade');
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
        Schema::dropIfExists('evaluationinternes');
    }
};
