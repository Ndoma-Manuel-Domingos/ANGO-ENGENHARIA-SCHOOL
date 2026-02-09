<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbControlePeriodicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_controle_periodicos', function (Blueprint $table) {
            $table->id();
            $table->string('trimestre', 200);
            $table->date('inicio');
            $table->date('final');
            $table->string('status')->default('activo');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_ano_lectivos_tb_trimestre')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_controle_trimestre_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_controle_periodicos');
    }
}
