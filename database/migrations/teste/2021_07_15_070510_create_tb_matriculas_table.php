<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_matriculas', function (Blueprint $table) {
            $table->id();
            $table->string('status', 200); //novo repitente
            $table->date('data_at');
            $table->string('ficha', 200)->default(time());
            $table->string('documento', 200);
            $table->string('status_matricula', 200);
            $table->unsignedBigInteger('at_classes_id');
            $table->foreign('at_classes_id')->references('id')->on('tb_classes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('classes_id');
            $table->foreign('classes_id')->references('id')->on('tb_classes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('turnos_id');
            $table->foreign('turnos_id')->references('id')->on('tb_turnos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('cursos_id');
            $table->foreign('cursos_id')->references('id')->on('tb_cursos')->onDelete('restrict')->onUpdate('restrict');
            $table->string('tipo')->default('matricula');// confirmacao ou matricula
            $table->integer('funcionarios_id');
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_matriculas');
    }
}
