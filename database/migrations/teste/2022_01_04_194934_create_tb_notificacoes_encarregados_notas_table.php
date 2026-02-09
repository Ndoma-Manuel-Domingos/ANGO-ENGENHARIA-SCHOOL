<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbNotificacoesEncarregadosNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_notificacoes_encarregados_notas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo',200)->nullable();
            $table->string('tipo', 50)->nullable();
            $table->date('data_at')->nullable();
            $table->string('visto', 20)->nullable();
            
            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');

            $table->unsignedBigInteger('trimestres_id');
            $table->foreign('trimestres_id')->references('id')->on('tb_controle_periodicos')->onDelete('restrict')->onUpdate('restrict');

            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');

            $table->unsignedBigInteger('encarregados_id');
            $table->foreign('encarregados_id')->references('id')->on('tb_encarregados')->onDelete('restrict')->onUpdate('restrict');

            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
            
            $table->text('descricao')->nullable();
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
        Schema::dropIfExists('tb_notificacoes_encarregados_notas');
    }
}
