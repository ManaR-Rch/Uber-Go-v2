<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'lue',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
