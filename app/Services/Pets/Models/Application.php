<?php

namespace App\Services\Pets\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Users\Models\User;

class Application extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}