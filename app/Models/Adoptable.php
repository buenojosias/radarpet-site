<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoptable extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','castrated','vaccinated','dewomed','details','status'];

    public function pet() {
        return $this->morphOne(Pet::class, 'petable');
    }

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    
    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }
    
}
