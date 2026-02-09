<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPagamentosOriginaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_pagamentos_originais', function (Blueprint $table) {
            $table->id();
            $table->string('pago_at', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('caixa_at', 50)->nullable();
            $table->string('ficha')->nullable();
            $table->foreignId('servicos_id')
            ->nullable()
            ->constrained('tb_servicos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->double('valor', 10, 2)->nullable();
            $table->double('valor2', 10, 2)->nullable();
            $table->double('valor_entregue', 10, 2)->nullable();
            $table->double('troco', 10, 2)->nullable();
            $table->double('desconto', 10, 2)->nullable();
            $table->double('multa', 10, 2)->nullable();
            $table->double('inss', 10, 2)->nullable();
            $table->double('irt', 10, 2)->nullable();
            $table->double('faltas', 10, 2)->nullable();
            $table->double('subcidio', 10, 2)->nullable();
            $table->double('subcidio_transporte', 10, 2)->nullable();
            $table->double('subcidio_alimentacao', 10, 2)->nullable();
            $table->double('subcidio_natal', 10, 2)->nullable();
            $table->double('subcidio_ferias', 10, 2)->nullable();
            $table->double('subcidio_abono_familiar', 10, 2)->nullable();

            $table->double('total_iva', 10, 2)->nullable();
            $table->double('valor_cash', 10, 2)->nullable();
            $table->double('valor_multicaixa', 10, 2)->nullable();
            $table->double('total_incidencia', 10, 2)->nullable();

            $table->string('valor_extenso', 255)->nullable();
            $table->string('texto_hash', 255)->nullable();
            $table->string('hash', 255)->nullable();

            $table->string('conta_corrente_cliente', 255)->nullable();
            $table->string('nif_cliente', 255)->nullable();
            $table->string('numero_factura', 255)->nullable();
            $table->string('tipo_factura', 255)->nullable();
            $table->string('codigo', 255)->nullable();
            $table->string('banco', 255)->nullable();
            $table->string('numero_transacao', 255)->nullable();
            $table->string('tipo_pagamento', 255)->nullable();
            $table->string('model', 255)->nullable();

            $table->string('motivo', 255)->nullable();
            $table->string('referencia', 255)->nullable();
            $table->string('observacao', 255)->nullable();
            $table->string('mensal', 255)->nullable();
            
            $table->date('data_at')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->date('data_disponibilizacao')->nullable();
            
            $table->string('next_factura', 50)->nullable();
            $table->string('factura_ano', 50)->nullable();
            $table->string('ano_factura', 50)->nullable();
            $table->string('prazo', 50)->nullable();
            $table->string('numeracao_proforma', 50)->nullable();
            
            $table->enum('retificado', ['Y', 'N'])->default('N')->comment('Y=>sim; N=>Não');
            $table->enum('convertido_factura', ['Y', 'N'])->default('N')->comment('Y=>sim; N=>Não');
            $table->enum('factura_divida', ['Y', 'N'])->default('N')->comment('Y=>sim; N=>Não');
            $table->enum('anulado', ['Y', 'N'])->default('N')->comment('Y=>sim; N=>Não');
            
            $table->foreignId('pagamento_id')
            ->nullable()
            ->constrained('tb_formas_pagamentos')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreignId('moeda')
            ->nullable()
            ->constrained('tb_moedas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->integer('quantidade');
            
            $table->string('funcionarios_id', 50)->nullable();
            $table->string('estudantes_id', 50)->nullable();

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
        Schema::dropIfExists('tb_pagamentos_originais');
    }
}
