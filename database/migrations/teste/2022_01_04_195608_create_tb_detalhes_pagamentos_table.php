<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetalhesPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalhes_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('status', 70)->nullable();
            $table->string('code', 100)->nullable();
            $table->string('mes_id', 100)->nullable();
            $table->string('mes', 100)->nullable();
            $table->string('quantidade', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->double('preco', 10, 2)->nullable();
            $table->double('multa', 10, 2)->nullable();
            $table->date('date_att')->nullable();
            $table->unsignedBigInteger('servicos_id');
            $table->foreign('servicos_id')->references('id')->on('tb_servicos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')->references('id')->on('tb_professores')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_detalhes_pagamentos');
    }
}
