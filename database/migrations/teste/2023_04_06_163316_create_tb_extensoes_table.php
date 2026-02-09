<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbExtensoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_extensoes', function (Blueprint $table) {
            $table->id();
            $table->string('extensao', 50)->nullable();
            $table->string('sufix', 70)->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->string('tipo', 50)->nullable();

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
        Schema::dropIfExists('tb_extensoes');
    }
}
