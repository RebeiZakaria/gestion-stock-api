<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Equipement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffectationController extends Controller
{
    public function index()
    {
        $affectations = Affectation::with(['equipement', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($affectations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'user_id' => 'required|exists:users,id',
            'date_affectation' => 'required|date',
            'commentaire' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $equipement = Equipement::findOrFail($request->equipement_id);
            $user = User::findOrFail($request->user_id);

            // Vérifier si l'équipement est disponible
            if ($equipement->quantite_disponible <= 0) {
                return response()->json([
                    'message' => 'Équipement non disponible'
                ], 400);
            }

            // Créer l'affectation
            $affectation = Affectation::create([
                'equipement_id' => $request->equipement_id,
                'user_id' => $request->user_id,
                'utilisateur_nom' => $user->name,
                'date_affectation' => $request->date_affectation,
                'commentaire' => $request->commentaire
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Équipement affecté avec succès',
                'affectation' => $affectation->load(['equipement', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Erreur lors de l\'affectation'
            ], 500);
        }
    }

    public function retourner(Request $request, $id)
    {
        $request->validate([
            'date_retour' => 'required|date',
            'commentaire' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $affectation = Affectation::findOrFail($id);
            
            $affectation->update([
                'date_retour' => $request->date_retour,
                'statut' => 'retournee',
                'commentaire' => $request->commentaire
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Équipement retourné avec succès',
                'affectation' => $affectation->load(['equipement', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Erreur lors du retour'
            ], 500);
        }
    }
}