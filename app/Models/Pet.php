<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = ['specie_id','race_id','petable','name','gender','age','size','primary_color','secondary_color','physical_details'];

    public function adoptable() {
        return $this->morphTo();
    }
    
    public function occurrence() {
        return $this->morphTo();
    }
    
    public function specie() {
        return $this->belongsTo(Specie::class);
    }

    public function race() {
        return $this->belongsTo(Race::class);
    }
}
