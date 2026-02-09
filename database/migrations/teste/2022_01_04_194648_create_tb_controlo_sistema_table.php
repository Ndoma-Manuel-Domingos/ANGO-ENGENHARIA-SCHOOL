<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbControloSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_controlo_sistema', function (Blueprint $table) {
            $table->id();
            $table->date('inicio')->nullable();
            $table->date('final')->nullable();
            $table->unsignedBigInteger('shcools_id')->nullable();
            $table->foreign('shcools_id')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_controlo_sistema');
    }
}
