<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_matriculas', function (Blueprint $table) {
            $table->id();

            $table->string('status', 100)->nullable();
            $table->string('ficha', 100)->nullable();
            $table->string('numero_estudante', 100)->nullable();
            $table->string('documento', 100)->nullable();
            $table->string('status_matricula', 100)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->string('condicao', 100)->nullable();
            $table->string('numeracao', 100)->nullable();
            $table->date('data_at')->nullable();

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

            $table->foreignId('cursos_id')
            ->nullable()
            ->constrained('tb_cursos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('turnos_id')
            ->nullable()
            ->constrained('tb_turnos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('at_classes_id')
            ->nullable()
            ->constrained('tb_classes')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreignId('classes_id')
            ->nullable()
            ->constrained('tb_classes')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('ano_lectivo_global_id')
            ->nullable()
            ->constrained('tb_ano_lectivos_global')
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
        Schema::dropIfExists('tb_matriculas');
    }
}
