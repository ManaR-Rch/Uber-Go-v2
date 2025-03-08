@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mes Trajets</h1>
        <a href="{{ route('trajets.create') }}" class="btn btn-primary">Créer un trajet</a>

        @foreach($trajets as $trajet)
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $trajet->lieu_depart }} → {{ $trajet->lieu_arrivee }}</h5>
                    <p class="card-text">Date : {{ $trajet->date_depart }}</p>
                    <p class="card-text">Places disponibles : {{ $trajet->places_disponibles }}</p>
                    <a href="{{ route('trajets.edit', $trajet->id) }}" class="btn btn-warning">Modifier</a>
                    <form action="{{ route('trajets.destroy', $trajet->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
