<?php

namespace App\Services\Pets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Pets\Models\Application;

class Pet extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'sterilized' => 'boolean',
        'temperament_tags' => 'array',
        'ideal_owner_tags' => 'array',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}