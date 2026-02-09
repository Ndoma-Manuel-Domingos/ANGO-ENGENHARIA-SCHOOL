<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProfessoresAcademicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_professores_academicos', function (Blueprint $table) {
            $table->id();

            $table->string('area_formacao')->nullable();
            $table->foreignId('cursos_id')
            ->nullable()
            ->constrained('tb_cursos_univeridades')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->foreignId('escolaridade_id')
            ->nullable()
            ->constrained('tb_escolaridades')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('formacao_academica_id')
            ->nullable()
            ->constrained('tb_formacao_acedemica')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('universidade_id')
            ->nullable()
            ->constrained('tb_universidades')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('professor_id')
            ->nullable()
            ->constrained('tb_professores')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('ano_lectivo_global_id')
            ->nullable()
            ->constrained('tb_ano_lectivos_global')
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
        Schema::dropIfExists('tb_professores_academicos');
    }
}
