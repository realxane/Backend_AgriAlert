<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // On récupère les notifications de l'utilisateur authentifié (via Sanctum)
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // On retourne la réponse avec l'encodage UTF-8 pour les accents
        return response()->json($notifications, 200, [
            'Content-Type' => 'application/json; charset=UTF-8'
        ], JSON_UNESCAPED_UNICODE);
    }

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Supprimé avec succès']);
        }

        return response()->json(['message' => 'Non trouvé'], 404);
    }

    // Tout supprimer
    public function deleteAll()
    {
        Notification::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'Toutes les notifications supprimées']);
    }
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['message' => 'Lue']);
        }
        return response()->json(['message' => 'Non trouvée'], 404);
    }
}