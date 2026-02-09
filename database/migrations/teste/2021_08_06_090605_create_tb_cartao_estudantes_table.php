<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCartaoEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cartao_estudantes', function (Blueprint $table) {
            $table->id();

            $table->date('data_at' ,20)->nullable();
            $table->date('data_exp' ,20)->nullable();
            $table->string('month_number' ,50)->nullable();
            $table->string('month_name' ,50)->nullable();
            $table->string('status', 20)->default('Nao Pago');

            $table->unsignedBigInteger('servicos_id');
            $table->foreign('servicos_id')->references('id')->on('tb_servicos')->onDelete('restrict')->onUpdate('restrict');
            
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id', 'fk_turmasestudantes_cartao_estudantes')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
            
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_admins_ano_lectivos__cartao_estudantes')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_cartao_estudantes');
    }
}
