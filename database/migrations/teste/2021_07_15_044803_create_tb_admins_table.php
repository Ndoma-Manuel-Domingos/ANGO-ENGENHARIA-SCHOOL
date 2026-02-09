<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbAdminsTable extends Migration
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
            $table->string('usuario', 200);
            $table->string('password', 200);
            $table->bigInteger('funcionarios_id', 200)->nullable();
            $table->string('acesso', 200);
            $table->string('status', 200);
            $table->unsignedBigInteger('shcools_id');
            $table->foreign('shcools_id', 'fk_admins_shcools')->references('id')->on('tb_shcools')->onDelete('restrict')->onUpdate('restrict');
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
