@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Trajets Disponibles</h1>
        <!-- Formulaire de recherche -->
        <form method="GET" action="{{ route('trajets.search') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="lieu_depart" class="form-control" placeholder="Lieu de départ">
                </div>
                <div class="col-md-4">
                    <input type="text" name="lieu_arrivee" class="form-control" placeholder="Lieu d'arrivée">
                </div>
                <div class="col-md-4">
                    <input type="date" name="date_depart" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Chercher</button>
        </form>

        <!-- Affichage des trajets disponibles -->
        <div class="mt-4">
            <h3>Résulta ts :</h3>
            @foreach($trajets as $trajet)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $trajet->lieu_depart }} → {{ $trajet->lieu_arrivee }}</h5>
                        <p class="card-text">Date : {{ $trajet->date_depart }}</p>
                        <p class="card-text">Places disponibles : {{ $trajet->places_disponibles }}</p>
                        <a href="{{ route('trajets.show', $trajet->id) }}" class="btn btn-info">Voir le trajet</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
