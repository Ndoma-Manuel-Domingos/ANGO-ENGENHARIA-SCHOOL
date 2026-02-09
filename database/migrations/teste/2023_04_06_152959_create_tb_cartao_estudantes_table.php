<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbCartaoEstudantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cartao_estudantes', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Pago', 'Nao Pago', 'processo'])->default('Nao Pago');
            $table->date('data_at')->nullable();
            $table->date('data_exp')->nullable();
            $table->string('month_number')->nullable();
            $table->string('month_name')->nullable();

            $table->bigInteger('mes_id')->nullable();
            
            $table->foreignId('estudantes_id')
            ->nullable()
            ->constrained('tb_estudantes')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            
            $table->foreignId('servicos_id')
            ->nullable()
            ->constrained('tb_servicos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            
            $table->foreignId('ano_lectivos_id')
            ->nullable()
            ->constrained('tb_ano_lectivos')
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
        Schema::dropIfExists('tb_cartao_estudantes');
    }
}
