<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTransferenciasEscolarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transferencias_escolar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estudantes_id')
            ->nullable()
            ->constrained('tb_estudantes')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('org_shcools_id')
            ->nullable()
            ->constrained('tb_shcools')
            ->onUpdate('cascade')
            ->onDelete('cascade');


            $table->foreignId('des_shcools_id')
            ->nullable()
            ->constrained('tb_shcools')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('user_id')
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

            $table->foreignId('classes_id')
            ->nullable()
            ->constrained('tb_classes')
            ->onUpdate('cascade')
            ->onDelete('cascade');


            $table->date('data_final')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('condicao', 50)->nullable();
            $table->string('documento', 100)->nullable();
            $table->fullText('motivo')->nullable();

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
        Schema::dropIfExists('tb_transferencias_escolar');
    }
}
