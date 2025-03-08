<?php

namespace App\Http\Controllers;

use App\Models\Disponibilite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisponibiliteController extends Controller
{
    /**
     * Display a listing of the user's disponibilites.
     */
    public function index()
    {
        $disponibilites = Disponibilite::where('chauffeur_id', Auth::id())->get();
        return response()->json($disponibilites);
    }

    /**
     * Store a new disponibilite.
     */
    public function store(Request $request)
    {
        // Ensure only chauffeurs can create disponibilites
        if (Auth::user()->role !== 'chauffeur') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'lieu' => 'required|string'
        ]);

        $disponibilite = Disponibilite::create([
            'chauffeur_id' => Auth::id(),
            'date' => $validatedData['date'],
            'heure_debut' => $validatedData['heure_debut'],
            'heure_fin' => $validatedData['heure_fin'],
            'lieu' => $validatedData['lieu']
        ]);

        return response()->json($disponibilite, 201);
    }

    /**
     * Display a specific disponibilite.
     */
    public function show($id)
    {
        $disponibilite = Disponibilite::findOrFail($id);
        
        // Ensure the user can only view their own disponibilite
        if ($disponibilite->chauffeur_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($disponibilite);
    }

    /**
     * Update an existing disponibilite.
     */
    public function update(Request $request, $id)
    {
        $disponibilite = Disponibilite::findOrFail($id);

        // Ensure the user can only update their own disponibilite
        if ($disponibilite->chauffeur_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'date' => 'sometimes|date',
            'heure_debut' => 'sometimes|date_format:H:i',
            'heure_fin' => 'sometimes|date_format:H:i|after:heure_debut',
            'lieu' => 'sometimes|string'
        ]);

        $disponibilite->update($validatedData);

        return response()->json($disponibilite);
    }

    /**
     * Delete a disponibilite.
     */
    public function destroy($id)
    {
        $disponibilite = Disponibilite::findOrFail($id);

        // Ensure the user can only delete their own disponibilite
        if ($disponibilite->chauffeur_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $disponibilite->delete();

        return response()->json(['message' => 'Disponibilité supprimée avec succès']);
    }

    /**
     * Search for available disponibilites.
     */
    public function search(Request $request)
    {
        $query = Disponibilite::query();

        if ($request->has('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        if ($request->has('lieu')) {
            $query->where('lieu', 'like', '%' . $request->input('lieu') . '%');
        }

        $disponibilites = $query->get();

        return response()->json($disponibilites);
    }
}