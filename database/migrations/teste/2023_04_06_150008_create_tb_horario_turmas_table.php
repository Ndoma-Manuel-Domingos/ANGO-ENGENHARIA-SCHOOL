<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHorarioTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_horario_turmas', function (Blueprint $table) {
            $table->id();
            $table->string('semanas_id', 100);
            $table->string('tempo', 100)->nullable();
            $table->string('hora_inicio', 50)->nullable();
            $table->string('hora_final', 50)->nullable();
            
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
        Schema::dropIfExists('tb_horario_turmas');
    }
}
