<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbFormasPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_formas_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 50)->nullable();
            $table->string('sigla_tipo_pagamento', 70)->nullable();
            $table->string('tipo_credito', 50)->nullable();
            $table->enum('tipo_credito', ['1', '2'])->default('2')->comment('1=>pagCredito; 2=>Não Credito');
            $table->integer('status_id', 50)->default(1);
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
        Schema::dropIfExists('tb_formas_pagamentos');
    }
}
