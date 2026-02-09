<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_estudantes', function (Blueprint $table) {
            $table->id();
            $table->string('documento', 100)->nullable();
            $table->string('numero_processo', 100)->nullable();
            $table->string('nome', 50);
            $table->string('sobre_nome', 50);
            $table->date('nascimento');
            $table->enum('genero', ['Masculino', 'Femenino'])->default('Masculino');
            $table->string('estado_civil', 50)->nullable();
            $table->string('nacionalidade', 50)->nullable();
            $table->string('bilheite', 50);
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->string('dificiencia', 50)->nullable();
            $table->string('provincia', 50)->nullable();
            $table->string('munincipio', 50)->nullable();
            $table->string('naturalidade', 50)->nullable();
            $table->string('pai', 50)->nullable();
            $table->string('mae', 50)->nullable();
            $table->string('telefone_estudante', 50)->nullable();
            $table->string('telefone_pai', 50)->nullable();
            $table->string('telefone_mae', 50)->nullable();
            $table->fullText('endereco')->nullable();
            $table->string('image', 100)->nullable();
            $table->string('conta_corrente', 50)->nullable();
            $table->string('registro', 70);

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
            
            $table->foreignId('ano_lectivos_id')
            ->nullable()
            ->constrained('tb_ano_lectivos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('ano_lectivo_global_id')
            ->nullable()
            ->constrained('tb_ano_lectivos_global')
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
        Schema::dropIfExists('tb_estudantes');
    }
}
