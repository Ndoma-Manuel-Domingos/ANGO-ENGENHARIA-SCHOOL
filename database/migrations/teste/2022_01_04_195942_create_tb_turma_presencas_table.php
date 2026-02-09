<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmaPresencasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turma_presencas', function (Blueprint $table) {
            $table->id();
            $table->date('data_at');
            $table->string('semanas_id', 200);
            $table->string('status')->default('activo');
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('disciplinas_id');
            $table->foreign('disciplinas_id')->references('id')->on('tb_disciplinas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')->references('id')->on('tb_professores')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_turma_presencas');
    }
}
