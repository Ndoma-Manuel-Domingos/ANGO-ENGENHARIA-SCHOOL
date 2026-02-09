<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cargos', function (Blueprint $table) {
            $table->id();

            $table->string('cargo', 50)->nullable();
            $table->double('salario', 10, 2)->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            
            $table->foreignId('departamento_id')
            ->nullable()
            ->constrained('tb_departamentos')
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
        Schema::dropIfExists('tb_cargos');
    }
}
