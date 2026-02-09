<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDiscplinasTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_discplinas_turmas', function (Blueprint $table) {
            $table->id();
            $table->string('status', 20)->default('activo');	
            $table->unsignedBigInteger('turmas_id');
            $table->foreign('turmas_id', 'fk_disciplinasturmas_turmas')->references('id')->on('tb_turmas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('disciplinas_id');
            $table->foreign('disciplinas_id', 'fk_disciplinasturmas_disciplinas')->references('id')->on('tb_disciplinas')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_discplinas_turmas');
    }
}
