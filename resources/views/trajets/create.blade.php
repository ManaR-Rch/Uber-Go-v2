@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un Trajet</h1>
        <form method="POST" action="{{ route('trajets.store') }}">
            @csrf
            <div class="form-group">
                <label for="lieu_depart">Lieu de départ</label>
                <input type="text" name="lieu_depart" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lieu_arrivee">Lieu d'arrivée</label>
                <input type="text" name="lieu_arrivee" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_depart">Date de départ</label>
                <input type="datetime-local" name="date_depart" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="places_disponibles">Places disponibles</label>
                <input type="number" name="places_disponibles" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success mt-2">Créer</button>
        </form>
    </div>
@endsection
