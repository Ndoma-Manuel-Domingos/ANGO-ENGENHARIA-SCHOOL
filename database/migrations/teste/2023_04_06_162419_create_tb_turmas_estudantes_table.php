<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurmasEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turmas_estudantes', function (Blueprint $table) {
            $table->id();
            $table->string('ordem', 50);
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

            $table->foreignId('ano_lectivos_id')
            ->nullable()
            ->constrained('tb_ano_lectivos')
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
        Schema::dropIfExists('tb_turmas_estudantes');
    }
}
