<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Securedata extends Model
{
    use HasFactory;
    protected $fillable = ['apptoken', 'ip','user_id', 'last_activity'];
}
