<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmasFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turmas_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('cargo_turma', 50);
            $table->unsignedBigInteger('disciplinas_id');
            $table->foreign('disciplinas_id')->references('id')->on('tb_disciplinas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')->references('id')->on('tb_professores')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_turmas_funcionarios');
    }
}
