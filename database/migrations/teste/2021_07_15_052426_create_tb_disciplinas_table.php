<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('disciplina', 200);
            $table->string('abreviacao', 100);
            $table->string('code', 100);
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_disciplinas_disciplinas')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_disciplinas');
    }
}
