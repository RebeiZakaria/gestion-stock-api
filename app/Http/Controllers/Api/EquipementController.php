<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class EquipementController extends Controller
{
    // Afficher tous les équipements
    public function index()
    {
        // Charger les équipements avec les affectations actives pour calculer la quantité disponible
        $equipements = Equipement::with(['affectationsActives'])->get();
        
        // Ajouter la quantité disponible à chaque équipement
        $equipements->each(function ($equipement) {
            $equipement->quantite_disponible = $equipement->quantite - $equipement->affectationsActives->count();
        });
        
        return response()->json($equipements);
    }

    // Ajouter un équipement
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'quantite' => 'required|integer|min:1',
            'num_marche_consultation' => 'nullable|string|max:255',
            'fournisseur' => 'nullable|string|max:255',
            'code_patrimoine' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'utilisateur' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_affectation' => 'nullable|date',
        ]);

        $equipement = Equipement::create($validated);
        return response()->json($equipement, 201);
    }


    // Afficher un équipement par ID
    public function show($id)
    {
        return Equipement::findOrFail($id);
    }

    // Mettre à jour un équipement
    public function update(Request $request, $id)
    {
        $equipement = Equipement::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'quantite' => 'required|integer|min:1',
            'num_marche_consultation' => 'nullable|string|max:255',
            'fournisseur' => 'nullable|string|max:255',
            'code_patrimoine' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'utilisateur' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_affectation' => 'nullable|date',
        ]);

        $equipement->update($validated);
        return response()->json($equipement, 200);
    }


    // Supprimer un équipement
    public function destroy($id)
    {
        Equipement::destroy($id);
        return response()->json(null, 204);
    }

    /**
     * Obtenir un équipement avec ses affectations
     */
    public function getWithAffectations($id)
    {
        $equipement = Equipement::with(['affectationsActives.user', 'affectations.user'])
            ->findOrFail($id);

        // Calculer la quantité disponible
        $equipement->quantite_disponible = $equipement->quantite - $equipement->affectationsActives->count();

        return response()->json($equipement);
    }
    
}
