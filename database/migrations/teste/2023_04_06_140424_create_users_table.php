<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');
            $table->string('password');
            $table->string('email')->unique()->nullable();
            $table->string('telefone')->nullable();
            $table->string('naome');
            $table->enum('login', ['Y','N'])->default('N');
            $table->string('acesso');
            $table->timestamp('lest_login')->nullable();
            $table->timestamp('lest_logout')->nullable();
            $table->enum('status', ['activo', 'desactivo'])->default('desactivo');
            $table->string('numero_avaliacoes')->default(0);
            $table->integer('level');

            $table->foreignId('funcionarios_id')
            ->nullable()
            ->constrained('tb_professores')
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
        Schema::dropIfExists('users');
    }
}
