<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNumMarcheColumnTypeInEquipementsTable extends Migration
{
    public function up()
    {
        Schema::table('equipements', function (Blueprint $table) {
            $table->string('num_marche_consultation')->change(); // ✅ Change to string
        });
    }

    public function down()
    {
        Schema::table('equipements', function (Blueprint $table) {
            $table->date('num_marche_consultation')->change(); // Restore original if needed
        });
    }
}
