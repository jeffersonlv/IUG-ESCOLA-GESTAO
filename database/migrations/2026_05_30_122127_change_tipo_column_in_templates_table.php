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
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            \DB::statement('ALTER TABLE templates MODIFY COLUMN tipo VARCHAR(20) NOT NULL');
            \DB::statement('CREATE INDEX templates_tipo_index ON templates (tipo)');
        } elseif ($driver === 'sqlite') {
            // SQLite: index only (no column length constraint)
            Schema::table('templates', function (Blueprint $table) {
                $table->index('tipo');
            });
        }
    }

    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            \DB::statement('DROP INDEX templates_tipo_index ON templates');
            \DB::statement('ALTER TABLE templates MODIFY COLUMN tipo VARCHAR(255) NOT NULL');
        } elseif ($driver === 'sqlite') {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropIndex(['tipo']);
            });
        }
    }
}
