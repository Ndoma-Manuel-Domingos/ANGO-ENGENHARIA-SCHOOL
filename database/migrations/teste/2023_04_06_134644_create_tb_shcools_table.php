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
            $table->string('nome');
            $table->string('cabecalho1')->nullable();
            $table->string('cabecalho2')->nullable();
            $table->string('director')->nullable();
            $table->string('documento')->unique();
            $table->string('site')->nullable();
            $table->string('sigla')->nullable();
            $table->string('status')->nullable();
            $table->enum('categoria', ['Privado', 'Publica']);
            $table->string('natureza')->nullable();
            $table->string('provincia')->nullable();

            $table->foreignId('pais_id')
            ->nullable()
            ->constrained('countries')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('provincia_id')
            ->nullable()
            ->constrained('countries')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->string('municipio')->nullable();
            $table->string('distrito')->nullable();
            $table->string('endereco')->nullable();
            $table->string('decreto')->nullable();
            $table->string('agua')->nullable();
            $table->string('eletricidade')->nullable();
            $table->string('cantina')->nullable();
            $table->string('biblioteca')->nullable();
            $table->string('campo_desportivo')->nullable();
            $table->string('transporte')->nullable();
            $table->string('telefone1')->nullable();
            $table->string('telefone2')->nullable();
            $table->string('telefone3')->nullable();
            $table->string('logotipo')->nullable();
            $table->string('logotipo2')->nullable();
            $table->string('logotipo_assinatura_director')->nullable();
            
            $table->string('numero_escola')->nullable();

            $table->foreignId('ano_lectivo_global_id')
            ->nullable()
            ->constrained('tb_ano_lectivos_global')
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
        Schema::dropIfExists('tb_shcools');
    }
}
