<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function specie() {
        return $this->belongsTo(Specie::class);
    }
    
}
