<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCartaoEscolaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cartao_escola', function (Blueprint $table) {

            $table->id();
            $table->string('month_number', 200);
            $table->string('month_name', 200);
            $table->date('data_at');
            $table->date('data_exp');
            $table->string('status', 20)->default('Nao Pago');
            $table->unsignedBigInteger('servicos_id');
            $table->foreign('servicos_id')->references('id')->on('tb_servicos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_cartao_escola');
    }
}
