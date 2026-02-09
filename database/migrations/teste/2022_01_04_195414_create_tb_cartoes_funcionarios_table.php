<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCartoesFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cartoes_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('mes_id', 200);
            $table->string('status', 20)->default('Nao Pago');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')->references('id')->on('tb_professores')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_cartoes_funcionarios');
    }
}
