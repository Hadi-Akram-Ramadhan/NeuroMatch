<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'mood', 'personality'
    ];

    protected $casts = [
        'personality' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
