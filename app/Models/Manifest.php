<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','occurrence_id','description'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function location() {
        return $this->morphOne(Location::class, 'locationable');
    }
}
