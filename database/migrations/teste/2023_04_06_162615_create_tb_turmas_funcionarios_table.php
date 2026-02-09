<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmasFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turmas_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('cargo_turma', 50);
            $table->date('tempo_edicao');

            $table->foreignId('disciplinas_id')
            ->nullable()
            ->constrained('tb_disciplinas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('turmas_id')
            ->nullable()
            ->constrained('tb_turmas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('funcionarios_id')
            ->nullable()
            ->constrained('tb_professores')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('ano_lectivos_id')
            ->nullable()
            ->constrained('tb_ano_lectivos')
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
        Schema::dropIfExists('tb_turmas_funcionarios');
    }
}
