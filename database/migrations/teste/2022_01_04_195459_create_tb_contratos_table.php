<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_contratos', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('activo');
            $table->string('documento', 100)->nullable();
            $table->double('salario', 10, 2)->nullable();
            $table->double('subcidio', 10, 2)->nullable();
            $table->double('subcidio_alimentacao', 10, 2)->nullable();
            $table->double('subcidio_transporte', 10, 2)->nullable();
            $table->double('subcidio_ferias', 10, 2)->nullable();
            $table->double('subcidio_natal', 10, 2)->nullable();
            $table->double('subcidio_abono_familiar', 10, 2)->nullable();
            $table->double('falta_por_dia', 10, 2)->nullable();

            $table->date('data_inicio_contrato')->nullable();
            $table->date('data_final_contrato')->nullable();
            $table->string('hora_entrada_contrato', 50)->nullable();
            $table->string('hora_saida_contrato', 50)->nullable();
            $table->string('cargo', 50)->nullable();
            $table->string('conta_bancaria', 50)->nullable();
            $table->string('status_contrato', 50)->nullable();
            $table->string('iban', 50)->nullable();
            $table->text('clausula')->nullable();
            $table->string('nif', 50)->nullable();
            $table->date('data_at')->nullable();

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
        Schema::dropIfExists('tb_contratos');
    }
}
