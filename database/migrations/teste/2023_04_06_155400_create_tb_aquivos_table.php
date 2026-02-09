<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_aquivos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('model_id', 50)->nullable();
            $table->string('model_type', 100)->nullable();
            $table->string('certificado', 50)->nullable();
            $table->string('bilheite', 50)->nullable();
            $table->string('atestado', 50)->nullable();
            $table->string('outros', 50)->nullable();
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
        Schema::dropIfExists('tb_aquivos');
    }
}
