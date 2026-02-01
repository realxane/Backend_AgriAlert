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
            'surface_totale' => Champ::sum('aire'),
            'notification_total' => DB::table('notifications')->count(),
            'diagnostics_ia_total' => DB::table('diagnostics_ia')->where('maladie_detectee', 'NOT LIKE', '%_Healthy')->count(),
        
        ]);
    }

    //  Données carte (champs)
    public function mapData(Request $request)
    {
        $regionId = $request->query('region_id'); //  query string

        $q = Champ::query()
            ->select('id', 'nom', 'latitude', 'longitude', 'aire', 'region_id');

        if ($regionId) {
            $q->where('region_id', $regionId);
        }

        return response()->json($q->get());
    }

    //  Analyser les données des champs avec des filtres-region
   public function analyserData(Request $request)
{
    $regionId = $request->input('region_id');
    if (!$regionId) {
        return response()->json(['error' => 'Le paramètre region_id est obligatoire'], 400);
    }

    return response()->json([
        'total_users' => User::whereHas('champs', function ($query) use ($regionId) {
            $query->where('region_id', $regionId);
        })->count(),
        'surface_totale' => Champ::where('region_id', $regionId)->sum('aire'),
        'notification_total' => DB::table('notifications')
            ->whereIn('user_id', function ($query) use ($regionId) {
                $query->select('user_id')->from('champs')->where('region_id', $regionId);
            })->count(),
        'diagnostics_ia_total' => DB::table('diagnostics_ia')
            ->where('maladie_detectee', 'NOT LIKE', '%_Healthy')
            ->whereIn('user_id', function ($query) use ($regionId) {
                $query->select('user_id')->from('champs')->where('region_id', $regionId);
            })->count(),
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
