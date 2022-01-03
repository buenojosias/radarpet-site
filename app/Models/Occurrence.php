<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','type','details','reward','status','occurred_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pet() {
        return $this->morphOne(Pet::class, 'petable');
    }

    public function location() {
        return $this->morphOne(Location::class, 'locationable');
    }

    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    
    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function manifests() {
        return $this->hasMany(Manifest::class);
    }
    
}
