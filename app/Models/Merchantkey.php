<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchantkey extends Model
{
    use HasFactory;
    protected $table = "merchantkey";
    protected $fillable = ['user_id','company_id', 'public_key', 'terno', 'created_at', 'updated_at', 'status'];
}
