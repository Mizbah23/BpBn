<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $table = 'bonuses';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
