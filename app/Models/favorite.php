<?php

namespace App\Models;

use GMP;
use Illuminate\Database\Eloquent\Model;

class favorite extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
