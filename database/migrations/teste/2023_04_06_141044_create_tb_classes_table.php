<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_classes', function (Blueprint $table) {
            $table->id();
            $table->string('classes', 50);
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->string('tipo')->nullable();
            $table->string('tipo_avaliacao_nota')->nullable();
            $table->string('categoria')->nullable();
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
        Schema::dropIfExists('tb_classes');
    }
}
