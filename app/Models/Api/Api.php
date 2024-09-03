<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model 
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['product', 'name', 'url', 'username', 'password', 'optional1', 'status', 'code', 'type'];

    protected static $logAttributes = ['product', 'name', 'url', 'username', 'password', 'optional1', 'status', 'code', 'type'];
    protected static $logOnlyDirty = true;
}   