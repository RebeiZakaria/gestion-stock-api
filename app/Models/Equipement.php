<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'marque',
        'modele',
        'quantite',
        'num_marche_consultation',
        'fournisseur',
        'code_patrimoine',
        'serial_number',
        'utilisateur',
        'lieu',
        'date_affectation'
    ];

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function affectationsActives()
    {
        return $this->hasMany(Affectation::class)->where('statut', 'active');
    }

    // Méthode pour obtenir la quantité disponible
    public function getQuantiteDisponibleAttribute()
    {
        return $this->quantite - $this->affectationsActives()->count();
    }
}