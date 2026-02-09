<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbServicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_servicos', function (Blueprint $table) {
            $table->id();
            $table->string('servico', 100);
            $table->string('unidade', 100)->nullable()->default('uni');
            $table->string('tipo', 100)->nullable()->default('S');
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->enum('contas', ['receita', 'dispesas'])->default('receita');
            $table->fullText('descricao', 100)->nullable();

            $table->foreignId('taxa_id')
            ->nullable()
            ->constrained('tb_taxas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('motivo_id')
            ->nullable()
            ->constrained('tb_motivos')
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
        Schema::dropIfExists('tb_servicos');
    }
}
