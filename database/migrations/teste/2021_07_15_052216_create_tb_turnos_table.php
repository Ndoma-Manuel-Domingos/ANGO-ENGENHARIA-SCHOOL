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
            $table->string('turno', 200);
            $table->string('status')->default('activo')->nullable();
            $table->string('horario')->nullable();
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_turnos_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
