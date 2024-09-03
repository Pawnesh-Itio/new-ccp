<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pindata extends Model
{
    use HasFactory;
    
    protected $fillable = ['pin','user_id'];
    
}
