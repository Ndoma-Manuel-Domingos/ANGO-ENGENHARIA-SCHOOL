<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_turnos', function (Blueprint $table) {
            $table->id();
            $table->string('turno', 100);
            $table->string('horario', 100)->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->fullText('descricao')->nullable();
            $table->string('tipo', 70)->nullable();
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
        Schema::dropIfExists('tb_turnos');
    }
}
