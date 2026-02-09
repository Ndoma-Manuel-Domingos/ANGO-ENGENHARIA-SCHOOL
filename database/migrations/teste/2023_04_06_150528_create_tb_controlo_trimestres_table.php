<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbControlePeriodicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_controle_periodicos', function (Blueprint $table) {
            $table->id();

            $table->string('trimestre', 50);
            $table->string('inicio', 50);
            $table->string('final', 50);
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
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
        Schema::dropIfExists('tb_controle_periodicos');
    }
}
