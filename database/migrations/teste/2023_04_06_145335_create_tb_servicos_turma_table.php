<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbServicosTurmaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_servicos_turma', function (Blueprint $table) {
            $table->id();
            $table->string('servico', 100);
            $table->string('model', 100)->nullable();
            $table->double('preco', 10, 2)->nullable();
            $table->double('multa', 10, 2)->nullable();
            $table->double('desconto', 10, 2)->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_final')->nullable();
            $table->integer('total_vezes')->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->enum('pagamento', ['mensal', 'unico'])->default('unico');

            $table->foreignId('servicos_id')
            ->nullable()
            ->constrained('tb_servicos')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('turmas_id')
            ->nullable()
            ->constrained('tb_turmas')
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
        Schema::dropIfExists('tb_servicos_turma');
    }
}
