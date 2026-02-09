<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAnoLectivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ano_lectivos', function (Blueprint $table) {
            $table->id();
            $table->string('ano', 200);
            $table->date('inicio');
            $table->date('final');
            $table->string('status', 20)->default('activo');
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_ano_lectivos');
    }
}
