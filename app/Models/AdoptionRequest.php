<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','adoptable_id','description','status'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
