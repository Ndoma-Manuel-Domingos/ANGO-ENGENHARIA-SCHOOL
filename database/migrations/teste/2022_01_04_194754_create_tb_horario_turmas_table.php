<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHorarioTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_horario_turmas', function (Blueprint $table) {
            $table->id();
            $table->string('semanas_id',50)->nullable();
            $table->string('tempo', 50)->nullable();
            $table->string('hora_inicio', 50)->nullable();
            $table->string('hora_final', 50)->nullable();
            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('disciplinas_id');
            $table->foreign('disciplinas_id')->references('id')->on('tb_disciplinas')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_horario_turmas');
    }
}
