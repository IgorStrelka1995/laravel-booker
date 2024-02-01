<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Boardroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', '=', '1');
    }
}
