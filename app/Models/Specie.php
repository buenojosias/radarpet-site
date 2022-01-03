<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function races() {
        return $this->hasMany(Race::class);
    }
}
