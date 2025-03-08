<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrajetController extends Controller
{
    /**
     * Display a listing of the user's trajets.
     */
    public function index()
    {
        $trajets = Trajet::where('user_id', Auth::id())->get();
        return response()->json($trajets);
    }

    /**
     * Store a new trajet.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date_depart' => 'required|date',
            'lieu_depart' => 'required|string',
            'lieu_arrivee' => 'required|string',
            'places_disponibles' => 'required|integer|min:1',
        ]);

        $trajet = Trajet::create([
            'user_id' => Auth::id(),
            'date_depart' => $validatedData['date_depart'],
            'lieu_depart' => $validatedData['lieu_depart'],
            'lieu_arrivee' => $validatedData['lieu_arrivee'],
            'places_disponibles' => $validatedData['places_disponibles'],
            'statut' => 'en attente'
        ]);

        return response()->json($trajet, 201);
    }

    /**
     * Display a specific trajet.
     */
    public function show($id)
    {
        $trajet = Trajet::findOrFail($id);
        
        // Ensure the user can only view their own trajet
        if ($trajet->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($trajet);
    }

    /**
     * Update an existing trajet.
     */
    public function update(Request $request, $id)
    {
        $trajet = Trajet::findOrFail($id);

        // Ensure the user can only update their own trajet
        if ($trajet->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'date_depart' => 'sometimes|date',
            'lieu_depart' => 'sometimes|string',
            'lieu_arrivee' => 'sometimes|string',
            'places_disponibles' => 'sometimes|integer|min:1',
            'statut' => 'sometimes|in:en attente,annulé,accepté'
        ]);

        $trajet->update($validatedData);

        return response()->json($trajet);
    }

    /**
     * Delete a trajet.
     */
    public function destroy($id)
    {
        $trajet = Trajet::findOrFail($id);

        // Ensure the user can only delete their own trajet
        if ($trajet->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trajet->delete();

        return response()->json(['message' => 'Trajet supprimé avec succès']);
    }

    /**
     * Search for available trajets.
     */
    public function search(Request $request)
    {
        $query = Trajet::query();

        if ($request->has('lieu_depart')) {
            $query->where('lieu_depart', 'like', '%' . $request->input('lieu_depart') . '%');
        }

        if ($request->has('lieu_arrivee')) {
            $query->where('lieu_arrivee', 'like', '%' . $request->input('lieu_arrivee') . '%');
        }

        if ($request->has('date_depart')) {
            $query->whereDate('date_depart', $request->input('date_depart'));
        }

        $trajets = $query->where('statut', 'en attente')
                         ->where('places_disponibles', '>', 0)
                         ->get();

        return response()->json($trajets);
    }
// Ajouter une réservation pour un passager
public function reserver(Request $request, $id)
{
    $trajet = Trajet::findOrFail($id);

    
    if ($trajet->passagers()->where('user_id', Auth::id())->exists()) {
        return response()->json(['message' => 'Vous avez déjà réservé ce trajet.'], 400);
    }

    
    if ($trajet->places_disponibles <= 0) {
        return response()->json(['message' => 'Aucune place disponible pour ce trajet.'], 400);
    }

   
    $trajet->passagers()->attach(Auth::id());

    
    $trajet->update([
        'places_disponibles' => $trajet->places_disponibles - 1
    ]);

    return response()->json(['message' => 'Réservation effectuée avec succès.'], 201);
}

// Annuler une réservation
public function annulerReservation($id)
{
    $trajet = Trajet::findOrFail($id);

    
    if (!$trajet->passagers()->where('user_id', Auth::id())->exists()) {
        return response()->json(['message' => 'Vous n\'avez pas réservé ce trajet.'], 400);
    }

   
    $trajet->passagers()->detach(Auth::id());

   
    $trajet->update([
        'places_disponibles' => $trajet->places_disponibles + 1
    ]);

    return response()->json(['message' => 'Réservation annulée avec succès.'], 200);
}

}