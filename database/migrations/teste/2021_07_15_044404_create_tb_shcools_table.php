<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbShcoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_shcools', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->string('documento', 200);
            $table->string('site', 200);
            $table->string('sigla', 200);
            $table->string('categoria', 200);
            $table->string('natureza', 200);
            $table->string('provincia', 200);
            $table->string('municipio', 200);
            $table->string('distrito', 200);
            $table->string('endereco', 200);
            $table->string('decreto', 200);
            $table->string('agua', 200);
            $table->string('eletricidade', 200);
            $table->string('cantina', 200);
            $table->string('biblioteca', 200);
            $table->string('campo_desportivo', 200);
            $table->string('transporte', 200);
            $table->string('telefone1', 200);
            $table->string('telefone2', 200);
            $table->string('telefone3', 200);
            $table->string('logotipo', 200);
            $table->string('numero_escola', 200);
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
        Schema::dropIfExists('tb_shcools');
    }
}
