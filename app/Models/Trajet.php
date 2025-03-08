<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_depart',
        'lieu_depart',
        'lieu_arrivee',
        'places_disponibles',
        'statut',
    ];

    // Relation avec les passagers via une table pivot
    public function passagers()
    {
        return $this->belongsToMany(User::class, 'reservations')->withTimestamps();
    }

    // Relation avec l'utilisateur en tant que chauffeur
    public function chauffeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
