<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEncarregadoEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_encarregado_estudantes', function (Blueprint $table) {
            $table->id();
            $table->string('grau_parentesco', 170)->nullable();
            $table->unsignedBigInteger('encarregados_id');
            $table->foreign('encarregados_id')->references('id')->on('tb_encarregados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('estudantes_id');
            $table->foreign('estudantes_id')->references('id')->on('tb_estudantes')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_encarregado_estudantes');
    }
}
