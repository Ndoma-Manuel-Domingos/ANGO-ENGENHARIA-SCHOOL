<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUniversidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_universidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');

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
        Schema::dropIfExists('tb_universidades');
    }
}
