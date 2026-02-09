<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbNotasPautasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_notas_pautas', function (Blueprint $table) {
            $table->id();
            $table->double('mac', 10, 2)->nullable();
            $table->double('npp', 10, 2)->nullable();
            $table->double('npt', 10, 2)->nullable();
            $table->double('mt', 10, 2)->nullable();
            $table->double('mt1', 10, 2)->nullable();
            $table->double('mt2', 10, 2)->nullable();
            $table->double('mt3', 10, 2)->nullable();

            $table->double('mfd', 10, 2)->nullable();
            $table->double('ne', 10, 2)->nullable();
            $table->double('nr', 10, 2)->nullable();
            $table->double('rf', 10, 2)->nullable();

            $table->string('status', 50);

            $table->foreignId('turmas_id')
                ->nullable()
                ->constrained('tb_turmas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('estudantes_id')
                ->nullable()
                ->constrained('tb_estudantes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('funcionarios_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('ano_lectivos_id')
                ->nullable()
                ->constrained('tb_ano_lectivos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('controlo_trimestres_id')
                ->nullable()
                ->constrained('tb_controle_periodicos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('disciplinas_id')
                ->nullable()
                ->constrained('tb_disciplinas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('descricao', 100);

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
        Schema::dropIfExists('tb_notas_pautas');
    }
}
