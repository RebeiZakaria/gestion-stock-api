<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('equipements', function (Blueprint $table) {
            // Rename column (requires doctrine/dbal)
            $table->renameColumn('date_achat', 'num_marche_consultation');

            // Add a new column
            $table->string('fournisseur')->nullable();
        });
    }

    public function down()
    {
        Schema::table('equipements', function (Blueprint $table) {
            // Revert column rename
            $table->renameColumn('num_marche_consultation', 'date_achat');

            // Drop the added column
            $table->dropColumn('fournisseur');
        });
    }
};

