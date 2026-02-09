<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCalendariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_calendarios', function (Blueprint $table) {
            $table->id();
            $table->float('valor_matricula', 8 ,2);
            $table->float('valor_confirmacao', 8 ,2);
            $table->float('valor_propina', 8 ,2);
            $table->bigInteger('dia_inicio');
            $table->bigInteger('dia_final');
            $table->string('status', 45)->default('nao_pago');
            $table->unsignedBigInteger('classes_id');
            $table->foreign('classes_id', 'fk_admins_classes_tb_calendarios')->references('id')->on('tb_classes')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('turnos_id');
            $table->foreign('turnos_id', 'fk_admins_turnos_tb_calendarios')->references('id')->on('tb_turnos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('cursos_id');
            $table->foreign('cursos_id', 'fk_admins_cursos_tb_calendarios')->references('id')->on('tb_cursos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ano_lectivos_id');
            $table->foreign('ano_lectivos_id', 'fk_admins_ano_lectivos_tb_calendarios')->references('id')->on('tb_ano_lectivos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('tb_calendarios');
    }
}
