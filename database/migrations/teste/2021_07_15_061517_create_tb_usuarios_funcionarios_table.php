<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUsuariosFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_professores', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->string('sobre_nome', 200);
            $table->date('nascimento', 200);
            $table->string('genero', 200);
            $table->string('estado_civil', 200);
            $table->string('nacionalidade', 200)->nullable();
            $table->string('bilheite', 200);
            $table->string('status', 40)->default('activo');
            $table->string('nif', 200)->nullable();
            // $table->string('curso', 200)->nullable();
            // $table->string('area_formacao', 200)->nullable();
            // $table->string('nivel_academico', 200)->nullable();
            // $table->string('formacao_pedagogica', 200)->nullable();
            // $table->string('universidade', 200)->nullable();
            // $table->string('email', 200)->nullable();
            $table->string('telefone', 200)->nullable();
            $table->string('endereco', 200)->nullable();
            $table->string('documento', 200)->nullable();
            $table->string('image', 200)->nullable();
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_estudantes_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_professores');
    }
}
