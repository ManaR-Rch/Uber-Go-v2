@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mes Réservations</h1>

        @foreach($reservations as $reservation)
            <div class="card mt-3"  >
                <div class="card-body">
                    <h5 class="card-title">{{ $reservation->trajet->lieu_depart }} → {{ $reservation->trajet->lieu_arrivee }}</h5>
                    <p class="card-text">Date : {{ $reservation->trajet->date_depart }}</p>
                    <p class="card-text">Statut : {{ $reservation->statut }}</p>
                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Annuler la réservation</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
