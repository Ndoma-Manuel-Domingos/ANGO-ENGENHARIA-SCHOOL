<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProfessoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_professores', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 50);
            $table->string('sobre_nome', 50);
            $table->date('nascimento');
            $table->enum('genero', ['Masculino', 'Femenino'])->default('Masculino');
            $table->string('estado_civil', 50)->nullable();
            $table->string('bilheite', 50);
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->string('dificiencia', 50)->nullable();
            $table->string('telefone', 50)->nullable();
            $table->fullText('endereco')->nullable();
            $table->string('image', 100)->nullable();
            $table->string('conta_corrente', 50)->nullable();

            $table->foreignId('pais_id')
            ->nullable()
            ->constrained('countries')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('provincia_id')
            ->nullable()
            ->constrained('states')
            ->onUpdate('cascade')
            ->onDelete('cascade');

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
        Schema::dropIfExists('tb_professores');
    }
}
