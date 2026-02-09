<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAnoLectivoCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ano_lectivo_cursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_anos_lectivos_cursos')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('cursos_id');
            $table->foreign('cursos_id', 'fk_cursos_anos_lectivos')->references('id')->on('tb_cursos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_ano_lectivo_cursos');
    }
}
