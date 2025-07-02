<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EegData extends Model
{
    protected $fillable = [
        'user_id', 'alpha', 'beta', 'gamma', 'theta', 'delta', 'timestamp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
