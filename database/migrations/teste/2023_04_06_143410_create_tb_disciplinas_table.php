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
            $table->string('disciplina', 100);
            $table->string('code', 100)->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->fullText('descricao', 100)->nullable();

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
        Schema::dropIfExists('tb_disciplinas');
    }
}
