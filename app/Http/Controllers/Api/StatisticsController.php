<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Vue d'ensemble générale des statistiques
     */
    public function overview()
    {
        try {
            $overview = [
                'total_equipments' => Equipement::count(),
                'total_quantity' => Equipement::sum('quantite'),
                'total_types' => Equipement::distinct('type')->count(),
                'total_brands' => Equipement::whereNotNull('marque')->distinct('marque')->count(),
                'recent_equipments' => Equipement::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
                'old_equipments' => Equipement::where('created_at', '<=', Carbon::now()->subYears(5))->count()
            ];

            return response()->json($overview);
        } catch (\Exception $e) {
            Log::error('Erreur overview: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la récupération des statistiques générales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques par type d'équipement
     */
    public function byType()
    {
        try {
            $typeStats = Equipement::select('type')
                ->selectRaw('COUNT(*) as count')
                ->selectRaw('SUM(quantite) as total_quantity')
                ->groupBy('type')
                ->orderBy('count', 'desc')
                ->get();

            return response()->json($typeStats);
        } catch (\Exception $e) {
            Log::error('Erreur byType: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la récupération des statistiques par type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques par marque
     */
    public function byBrand()
    {
        try {
            $brandStats = Equipement::select('marque')
                ->selectRaw('COUNT(*) as count')
                ->selectRaw('SUM(quantite) as total_quantity')
                ->whereNotNull('marque')
                ->where('marque', '!=', '')
                ->groupBy('marque')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            return response()->json($brandStats);
        } catch (\Exception $e) {
            Log::error('Erreur byBrand: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la récupération des statistiques par marque',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyse de l'âge des équipements (basée sur created_at)
     */
    public function ageAnalysis()
    {
        try {
            $ageRanges = [
                'less_than_1_year' => 0,
                'one_to_three_years' => 0,
                'three_to_five_years' => 0,
                'more_than_five_years' => 0
            ];

            $equipments = Equipement::all();

            foreach ($equipments as $equipment) {
                $createdDate = Carbon::parse($equipment->created_at);
                $ageInYears = $createdDate->diffInYears(Carbon::now());

                if ($ageInYears < 1) {
                    $ageRanges['less_than_1_year']++;
                } elseif ($ageInYears < 3) {
                    $ageRanges['one_to_three_years']++;
                } elseif ($ageInYears < 5) {
                    $ageRanges['three_to_five_years']++;
                } else {
                    $ageRanges['more_than_five_years']++;
                }
            }

            return response()->json($ageRanges);
        } catch (\Exception $e) {
            Log::error('Erreur ageAnalysis: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de l\'analyse de l\'âge des équipements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Équipements récents et anciens (par created_at)
     */
    public function recentAndOldEquipments()
    {
        try {
            $recentEquipments = Equipement::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'nom', 'type', 'marque', 'created_at']);

            $oldEquipments = Equipement::orderBy('created_at', 'asc')
                ->limit(5)
                ->get(['id', 'nom', 'type', 'marque', 'created_at']);

            return response()->json([
                'recent' => $recentEquipments,
                'oldest' => $oldEquipments
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur recentAndOldEquipments: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la récupération des équipements récents et anciens',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toutes les statistiques (centralisé pour tableau de bord)
     */
    public function dashboard()
    {
        try {
            $data = [
                'overview' => $this->overview()->getData(),
                'by_type' => $this->byType()->getData(),
                'by_brand' => $this->byBrand()->getData(),
                'age_analysis' => $this->ageAnalysis()->getData()
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erreur dashboard: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la récupération du tableau de bord des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
