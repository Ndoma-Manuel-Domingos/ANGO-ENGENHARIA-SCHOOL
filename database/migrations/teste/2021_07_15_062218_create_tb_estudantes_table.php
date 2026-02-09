<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_estudantes', function (Blueprint $table) {
            $table->id();
            $table->string('documento', 200);
            $table->string('nome', 200);
            $table->string('sobre_nome', 200);
            $table->date('nascimento', 200);
            $table->string('genero', 200);
            $table->string('estado_civil', 200);
            $table->string('nacionalidade', 200)->nullable();
            $table->string('bilheite', 200);
            $table->string('status', 40)->default('activo');
            $table->string('dificiencia', 200)->nullable();
            $table->string('provincia', 200)->nullable();
            $table->string('munincipio', 200)->nullable();
            $table->string('naturalidade', 200)->nullable();
            $table->string('pai', 200)->nullable();
            $table->string('mae', 200)->nullable();
            $table->string('telefone_estudante', 200)->nullable();
            $table->string('telefone_pai', 200)->nullable();
            $table->string('telefone_mae', 200)->nullable();
            $table->string('endereco', 200)->nullable();
            $table->string('image', 200)->nullable();
            $table->string('registro', 200)->nullable();
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('shcools_id');
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
        Schema::dropIfExists('tb_estudantes');
    }
}
