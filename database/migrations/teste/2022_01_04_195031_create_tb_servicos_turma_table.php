<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbServicosTurmaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_servicos_turma', function (Blueprint $table) {
            $table->id();
            $table->string('model', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('pagamento', 150)->nullable();
            $table->double('preco', 10, 2)->nullable();
            $table->double('multa', 10, 2)->nullable();
            $table->double('desconto', 10, 2)->nullable();

            $table->date('data_inicio')->nullable();
            $table->date('data_final')->nullable();

            $table->bigInteger('total_vezes')->nullable();

            $table->unsignedBigInteger('servicos_id');
            $table->foreign('servicos_id')->references('id')->on('tb_servicos')->onDelete('restrict')->onUpdate('restrict');

            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            
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
        Schema::dropIfExists('tb_servicos_turma');
    }
}
