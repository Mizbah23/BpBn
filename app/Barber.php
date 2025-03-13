<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;
    protected $table = 'barbers';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
