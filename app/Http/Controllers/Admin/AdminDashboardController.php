<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Champ;
use DB;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;//on doit d'abord instalelr:composer require barryvdh/laravel-dompdf pour pdf
use Maatwebsite\Excel\Facades\Excel;//on installe d'abord:composer require maatwebsite/excel
use App\Exports\UsersExport;



class AdminDashboardController extends Controller
{
    //  Statistiques globales
    public function stats()
    {
        return response()->json([
            'total_users' => User::count(),
            'surface_totale' => Champ::sum('aire')
        ]);
    }

    //  Données carte (champs)
    public function mapData()
    {
        return response()->json(
            Champ::select('id', 'nom', 'latitude', 'longitude', 'aire')->get()
        );
    }
    //  Analyser les données des champs avec des filtres
    public function analyserData(Request $request)
    {
        $query = Champ::query();
        // Filtre par région si présent dans la requête
        if ($request->has('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        // Filtre par date si présent
        if ($request->has('date_debut')) {
            $query->where('created_at', '>=', $request->date_debut);
        }
        return response()->json([
            'surface_filtree' => $query->sum('aire'),
            'details' => $query->with('user')->get()
        ]);
    }
    // Exporter les données des utilisateurs et leurs champs en PDF
    public function exportPDF()
    {
        $users = User::with('champs')->withSum('champs', 'aire')->get();
        
        $pdf = Pdf::loadView('reports.users', compact('users'));
        
        return $pdf->download('rapport-agrialert.pdf');
    }
    // Exporter les données des utilisateurs et leurs champs en Excel
    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'utilisateurs.xlsx');
    }
}
