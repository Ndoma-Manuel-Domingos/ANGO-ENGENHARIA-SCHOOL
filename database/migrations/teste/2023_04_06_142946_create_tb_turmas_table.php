<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turmas', function (Blueprint $table) {
            $table->id();

            $table->string('turma', 100);
            $table->string('numero_maximo', 100)->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');

            $table->foreignId('salas_id')
            ->nullable()
            ->constrained('tb_salas')
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
            
            $table->foreignId('classes_id')
            ->nullable()
            ->constrained('tb_classes')
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
        Schema::dropIfExists('tb_turmas');
    }
}
