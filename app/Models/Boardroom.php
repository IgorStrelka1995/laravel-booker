<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boardroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
