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
        // Adiciona coluna apenas se ainda não existir
        if (!Schema::hasColumn('cursos', 'slug')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('titulo');
            });
        }

        // Popula slugs nulos ou vazios
        DB::table('cursos')->whereNull('slug')->orWhere('slug', '')->orderBy('id')->each(function ($curso) {
            $base = Str::slug($curso->titulo . '-' . $curso->id, '-');
            $str  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $base);
            $str  = preg_replace('/[^a-zA-Z0-9\s_-]/', '', $str);
            $str  = trim(preg_replace('/[\s_-]+/', '_', $str), '_');
            DB::table('cursos')->where('id', $curso->id)->update(['slug' => strtolower($str)]);
        });

        // Unique index — ignora se já existir
        try {
            Schema::table('cursos', function (Blueprint $table) {
                $table->unique('slug', 'cursos_slug_unique');
            });
        } catch (\Exception $e) {
            // index already exists
        }
    }

    public function down()
    {
        try {
            DB::statement('ALTER TABLE cursos DROP INDEX cursos_slug_unique');
        } catch (\Exception $e) {}

        if (Schema::hasColumn('cursos', 'slug')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
}
