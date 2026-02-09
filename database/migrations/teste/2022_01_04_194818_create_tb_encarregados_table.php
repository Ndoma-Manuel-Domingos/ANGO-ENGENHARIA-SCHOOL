<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEncarregadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_encarregados', function (Blueprint $table) {
            $table->id();
            $table->string('status', 70)->nullable();
            $table->string('nome', 100)->nullable();
            $table->string('sobre_nome', 100)->nullable();
            $table->string('estado_civil', 100)->nullable();
            $table->string('genero', 100)->nullable();
            $table->date('nascimento')->nullable();
            $table->string('profissao', 100)->nullable();
            $table->string('telefone', 100)->nullable();
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
        Schema::dropIfExists('tb_encarregados');
    }
}
