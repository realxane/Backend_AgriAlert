<?php
namespace App\Http\Controllers\Api\IA;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatBotController extends Controller
{
    public function ask(Request $request)
    {
        $user = $request->user();
        $question = $request->input('question');
        $maladie = $request->input('maladie_detectee');

        // 1. RÉCUPÉRATION DE L'HISTORIQUE
        // On prend les 5 derniers échanges de cet utilisateur pour cette maladie
        $historique = ChatMessage::where('user_id', $user->id)
            ->where('maladie_detectee', $maladie)
            ->latest()
            ->take(5)
            ->get()
            ->reverse();

        // Préparation des messages pour l'IA
        $messages = [];
        
        // Instruction Système (Gestion de la langue et expertise)
        $messages[] = [
            'role' => 'system', 
            'content' => "Tu es un expert agronome au Mali. Réponds en français simple. 
                          IMPORTANT : Pour les termes techniques ou les noms de plantes, 
                          donne toujours la traduction ou l'équivalent en Bambara entre parenthèses.
                          Si possible, suggère des liens vers des ressources de l'IER (Mali)."
        ];

        // Ajout de l'historique au flux de conversation
        foreach ($historique as $msg) {
            $messages[] = ['role' => 'user', 'content' => $msg->question];
            $messages[] = ['role' => 'assistant', 'content' => $msg->reponse];
        }

        // Ajout de la question actuelle
        $messages[] = ['role' => 'user', 'content' => "Ma plante a le/la $maladie. Ma question : $question"];

        // 2. APPEL API (Exemple avec OpenAI)
        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => $messages,
        ]);

        if ($response->successful()) {
            $reponseIA = $response->json()['choices'][0]['message']['content'];

            // 3. SAUVEGARDE DANS LA BASE DE DONNÉES
            ChatMessage::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'maladie_detectee' => $maladie,
                'question' => $question,
                'reponse' => $reponseIA,
            ]);

            return response()->json(['Reponse' => $reponseIA]);
        }

        return response()->json(['error' => 'Échec'], 500);
    }
}