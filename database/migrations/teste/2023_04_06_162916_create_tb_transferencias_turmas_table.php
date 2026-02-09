<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbTransferenciasTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transferencias_turmas', function (Blueprint $table) {
            $table->id();

            $table->string('status', 50)->nullable();
            $table->string('documento', 70)->nullable();
            $table->string('motivo', 255)->nullable();

            $table->foreignId('estudantes_id')
            ->nullable()
            ->constrained('tb_estudantes')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('org_turmas_id')
            ->nullable()
            ->constrained('tb_turmas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('des_turmas_id')
            ->nullable()
            ->constrained('tb_turmas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
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
        Schema::dropIfExists('tb_transferencias_turmas');
    }
}
