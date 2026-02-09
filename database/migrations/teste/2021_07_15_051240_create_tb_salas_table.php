<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_salas', function (Blueprint $table) {
            $table->id();
            $table->string('salas', 200);
            $table->string('tipo', 200);
            $table->string('status')->default('activo');
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_salas_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_salas');
    }
}
