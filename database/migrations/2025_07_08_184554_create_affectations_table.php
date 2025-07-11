// Migration pour créer la table affectations
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffectationsTable extends Migration
{
    public function up()
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')->constrained('equipements')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('utilisateur_nom'); // Nom de l'utilisateur au moment de l'affectation
            $table->date('date_affectation');
            $table->date('date_retour')->nullable();
            $table->enum('statut', ['active', 'retournee'])->default('active');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affectations');
    }
}