<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDownloadCounters extends Migration
{
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('downloads')->default(0)->after('ordem');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->unsignedBigInteger('flyer_downloads')->default(0)->after('folder_pdf');
        });
    }

    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('downloads');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('flyer_downloads');
        });
    }
}
