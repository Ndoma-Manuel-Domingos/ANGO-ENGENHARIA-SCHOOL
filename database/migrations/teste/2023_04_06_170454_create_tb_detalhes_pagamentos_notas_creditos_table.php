<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDetalhesPagamentosNotasCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_detalhes_pagamentos_notas_creditos', function (Blueprint $table) {
            $table->id();
            $table->string('status', 100)->nullable();
            $table->string('code', 100)->nullable();
            $table->string('mes_id', 100)->nullable();
            $table->string('mes', 100)->nullable();
            $table->string('quantidade', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->integer('model_id')->nullable();
            $table->double('preco', 10, 2)->nullable();
            $table->double('valor_iva', 10, 2)->nullable();
            $table->double('valor_incidencia', 10, 2)->nullable();
            $table->double('total_pagar', 10, 2)->nullable();
            $table->double('desconto', 10, 2)->nullable();
            $table->double('desconto_valor', 10, 2)->nullable();
            $table->double('taxa_id', 10, 2)->nullable();
            $table->double('multa', 10, 2)->nullable();

            $table->date('date_att')->nullable();

            $table->foreignId('servicos_id')
            ->nullable()
            ->constrained('tb_servicos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('pagamentos_id')
            ->nullable()
            ->constrained('tb_pagamentos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('funcionarios_id')
            ->nullable()
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('ano_lectivos_id')
            ->nullable()
            ->constrained('tb_ano_lectivos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('shcools_id')
            ->nullable()
            ->constrained('tb_shcools')
            ->onUpdate('cascade')
            ->onDelete('cascade');

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
        Schema::dropIfExists('tb_detalhes_pagamentos_notas_creditos');
    }
}
