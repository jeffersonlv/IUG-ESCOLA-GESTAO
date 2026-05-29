<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoCursoTable extends Migration
{
    public function up()
    {
        Schema::create('aluno_curso', function (Blueprint $table) {
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->primary(['aluno_id', 'curso_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('aluno_curso');
    }
}
