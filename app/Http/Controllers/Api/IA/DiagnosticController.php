<?php

namespace App\Http\Controllers\Api\IA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\DiagnosticIa;
use Illuminate\Support\Str;

class DiagnosticController extends Controller
{
    public function detect(Request $request)
    {
        // 1. Validation
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $file = $request->file('image');

        // 2. Stockage de l'image
        $path = $file->store('diagnostics', 'public');
        $fullUrl = asset('storage/' . $path);

        try {
            // 3. Envoi vers ton microservice Flask
            $response = Http::attach(
                'image', 
                file_get_contents($file->getRealPath()), 
                $file->getClientOriginalName()
            )->post(env('AI_SERVICE_URL')); // URL définie dans le .env

            if ($response->successful()) {
                $result = $response->json();

                // 4. Sauvegarde avec TES noms de colonnes
                $diagnostic = DiagnosticIa::create([
                    'id' => Str::uuid(),
                    'user_id' => $request->user()->id,
                    'image_url' => $fullUrl,
                    'maladie_detectee' => $result['disease'],
                    'confidence' => $result['confidence'] * 100, // On stocke en pourcentage
                    'recommendation_ia' => $result['advice']
                ]);

                return response()->json([
                    'status' => 'success',
                    'resultat' => $diagnostic
                ]);
            }

            return response()->json(['error' => 'Erreur du service IA'], 502);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}