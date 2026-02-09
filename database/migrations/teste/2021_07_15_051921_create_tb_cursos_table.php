<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cursos', function (Blueprint $table) {
            $table->id();
            $table->string('curso', 200);
            $table->string('abreviacao', 100);
            $table->string('tipo', 100);
            $table->string('area_formacao');
            $table->string('status')->default('activo')->nullable();
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_cursos_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_cursos');
    }
}
