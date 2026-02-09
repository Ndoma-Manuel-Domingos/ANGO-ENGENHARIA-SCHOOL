<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbNotasPautasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_notas_pautas', function (Blueprint $table) {
            $table->id();

            $table->float('mac')->nullable();
            $table->float('npt')->nullable();
            $table->float('mt')->nullable();
            $table->float('mt1')->nullable();
            $table->float('mt2')->nullable();
            $table->float('mt3')->nullable();

            $table->float('mfd')->nullable();
            $table->float('ne')->nullable();
            $table->float('nr')->nullable();
            $table->float('rf')->nullable();

            $table->float('conf_pro')->nullable();
            $table->float('conf_ped')->nullable();

            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id', 'fk_notas_turmas')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id', 'fk_notas_estudantes')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id', 'fk_notas_funcionarios')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_notas_anos_lectivos')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('controlo_trimestres_id');
            $table->foreign('controlo_trimestres_id', 'fk_notas_controlo_trimestres')->references('id')->on('tb_controle_periodicos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('disciplinas_id');
            $table->foreign('disciplinas_id', 'fk_notas_disciplinas')->references('id')->on('tb_disciplinas')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_notas_pautas');
    }
}
