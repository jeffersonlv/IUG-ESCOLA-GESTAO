<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('templates');
    }

    public function down()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20);
            $table->string('nome');
            $table->string('fundo')->nullable();
            $table->integer('largura_mm')->default(210);
            $table->integer('altura_mm')->default(297);
            $table->string('orientacao')->default('portrait');
            $table->json('layout')->nullable();
            $table->boolean('ativo')->default(false);
            $table->timestamps();
        });
    }
}
