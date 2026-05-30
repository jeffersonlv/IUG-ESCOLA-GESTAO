<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTipoColumnInTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MySQL only — SQLite ignores column length; index still applied
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('templates', function (Blueprint $table) {
                $table->string('tipo', 20)->change();
            });
        }
        Schema::table('templates', function (Blueprint $table) {
            $table->index('tipo');
        });
    }

    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropIndex(['tipo']);
        });
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('templates', function (Blueprint $table) {
                $table->string('tipo')->change();
            });
        }
    }
}
