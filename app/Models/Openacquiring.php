<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Openacquiring extends Model
{
    use HasFactory;
    protected $table = 'openacquiring';
    protected $fillable = ['user_id', 'mobile', 'email', 'merchant_id', 'client_id', 'client_secret', 'status'];
}
