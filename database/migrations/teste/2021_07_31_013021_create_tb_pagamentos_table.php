<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('pago_at', 200);
            $table->unsignedBigInteger('servicos_id')->nullable();
            $table->foreign('servicos_id')->references('id')->on('tb_servicos')->onDelete('restrict')->onUpdate('restrict');
            $table->bigInteger('quantidade')->nullable();
            $table->string('status', 100)->nullable(); // confirmado, a prazo
            $table->string('caixa_at', 100)->nullable(); // REceitas ou despesas
            $table->string('ficha', 100)->nullable(); 
            $table->double('valor', 10, 2)->nullable();
            $table->double('valor2', 10, 2)->nullable();
            $table->float('desconto', 10, 2)->nullable();
            $table->float('multa', 10, 2)->nullable();

            $table->double('inss', 10, 2)->nullable();
            $table->double('irt', 10, 2)->nullable();
            $table->float('faltas', 10, 2)->nullable();
            $table->double('subcidio', 10, 2)->nullable();
            $table->double('subcidio_transporte', 10, 2)->nullable();
            $table->double('subcidio_alimentacao', 10, 2)->nullable();
            $table->double('subcidio_natal', 10, 2)->nullable();
            $table->double('subcidio_ferias', 10, 2)->nullable();
            $table->double('subcidio_abono_familiar', 10, 2)->nullable();

            $table->string('banco', 200)->nullable();
            $table->string('numero_transacao', 200)->nullable(); // BAnco
            $table->string('tipo_pagamento', 200);
            $table->string('model', 200)->nullable();
            $table->date('data_at');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id', 'fk_matriculas_estudantes_pagamentos')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_admins_ano_lectivos_pagamentos')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_pagamentos');
    }
}
