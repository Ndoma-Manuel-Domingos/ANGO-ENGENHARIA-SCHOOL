<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turmas', function (Blueprint $table) {
            $table->id();
            $table->string('turma', 20);
            $table->string('numero_maximo', 20);
            $table->string('status', 45)->default('activo');
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_admins_shcools_tb_turmas')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('classes_id');
            $table->foreign('classes_id', 'fk_admins_classes_tb_turmas')->references('id')->on('tb_classes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('turnos_id');
            $table->foreign('turnos_id', 'fk_admins_turnos_tb_turmas')->references('id')->on('tb_turnos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('cursos_id');
            $table->foreign('cursos_id', 'fk_admins_cursos_tb_turmas')->references('id')->on('tb_cursos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('salas_id');
            $table->foreign('salas_id', 'fk_admins_salas_tb_turmas')->references('id')->on('tb_salas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_admins_ano_lectivos_tb_turmas')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_turmas');
    }
}
