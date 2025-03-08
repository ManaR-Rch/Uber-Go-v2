@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mon Profil</h1>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
            </div>
            <button type="submit" class="btn btn-success mt-2">Mettre à jour</button>
        </form>
    </div>
@endsection
