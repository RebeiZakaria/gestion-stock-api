<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipement_id',
        'user_id',
        'utilisateur_nom',
        'equipement_nom',      // Nouveau
        'equipement_type',     // Nouveau
        'date_affectation',
        'date_retour',
        'statut',
        'commentaire'
    ];

    protected $casts = [
        'date_affectation' => 'date',
        'date_retour' => 'date',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthode pour obtenir le nom de l'équipement (même si supprimé)
    public function getEquipementNomAttribute()
    {
        return $this->equipement ? $this->equipement->nom : $this->attributes['equipement_nom'];
    }

    // Méthode pour obtenir le type de l'équipement (même si supprimé)
    public function getEquipementTypeAttribute()
    {
        return $this->equipement ? $this->equipement->type : $this->attributes['equipement_type'];
    }
}