<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFournisseurColumnInEquipementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('equipements', function (Blueprint $table) {
        $table->renameColumn('Fournisseur', 'fournisseur');
    });
}

public function down()
{
    Schema::table('equipements', function (Blueprint $table) {
        $table->renameColumn('fournisseur', 'Fournisseur');
    });
}

}
