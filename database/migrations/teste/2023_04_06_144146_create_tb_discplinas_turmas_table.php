<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDiscplinasTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_discplinas_turmas', function (Blueprint $table) {
            $table->id();

            $table->string('status')->nullable();

            $table->foreignId('disciplinas_id')
            ->nullable()
            ->constrained('tb_disciplinas')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('turmas_id')
            ->nullable()
            ->constrained('tb_turmas')
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
        Schema::dropIfExists('tb_discplinas_turmas');
    }
}
