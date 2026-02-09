<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAnoLectivoDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_ano_lectivo_disciplinas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('disciplinas_id')
            ->nullable()
            ->constrained('tb_disciplinas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('ano_lectivos_id')
            ->nullable()
            ->constrained('tb_ano_lectivos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

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
        Schema::dropIfExists('tb_ano_lectivo_disciplinas');
    }
}
