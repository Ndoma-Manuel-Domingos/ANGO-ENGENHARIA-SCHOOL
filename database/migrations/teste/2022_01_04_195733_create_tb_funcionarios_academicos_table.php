<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFuncionariosAcademicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_funcionarios_academicos', function (Blueprint $table) {
            $table->id();
            $table->string('curso', 200)->nullable();
            $table->string('area_formacao', 200)->nullable();
            $table->string('nivel_academico', 200)->nullable();
            $table->string('formacao_pedagogica', 200)->nullable();
            $table->string('universidade', 200)->nullable();
            $table->string('email', 200)->nullable();
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
        Schema::dropIfExists('tb_funcionarios_academicos');
    }
}
