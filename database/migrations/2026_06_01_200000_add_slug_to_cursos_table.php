<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddSlugToCursosTable extends Migration
{
    public function up()
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('titulo');
        });

        // Popula slugs para cursos existentes
        DB::table('cursos')->orderBy('id')->each(function ($curso) {
            $slug = strtolower(preg_replace(
                '/[\s_-]+/', '_',
                trim(preg_replace(
                    '/[^a-zA-Z0-9\s_-]/', '',
                    iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', Str::slug($curso->titulo . '-' . $curso->id, '-'))
                ), '_')
            ));
            DB::table('cursos')->where('id', $curso->id)->update(['slug' => $slug]);
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
