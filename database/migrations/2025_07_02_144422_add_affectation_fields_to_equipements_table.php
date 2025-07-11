<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffectationFieldsToEquipementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('equipements', function (Blueprint $table) {
        $table->string('code_patrimoine')->nullable();
        $table->string('serial_number')->nullable();
        $table->string('utilisateur')->nullable(); // ou foreign key vers une table utilisateurs
        $table->string('lieu')->nullable();
        $table->date('date_affectation')->nullable();
    });
}

public function down()
{
    Schema::table('equipements', function (Blueprint $table) {
        $table->dropColumn([
            'code_patrimoine',
            'serial_number',
            'utilisateur',
            'lieu',
            'date_affectation',
        ]);
    });
}

}
