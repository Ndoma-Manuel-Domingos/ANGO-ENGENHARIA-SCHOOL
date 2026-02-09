<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmasEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turmas_estudantes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ordem');
            $table->string('status', 30)->default('activo');
            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id', 'fk_turmasestudantes_turmas')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id', 'fk_turmasestudantes_estudantes')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_turmas_estudantes');
    }
}
