<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Openacquiring extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $table = 'openacquiring';
    protected $fillable = ['user_id', 'mobile', 'email', 'merchant_id', 'client_id', 'client_secret', 'status'];
}
