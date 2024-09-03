<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;
    protected $fillable = ['product', 'name', 'url', 'username', 'password', 'optional1', 'status', 'code', 'type'];

    protected static $logAttributes = ['product', 'name', 'url', 'username', 'password', 'optional1', 'status', 'code', 'type'];
    protected static $logOnlyDirty = true;
}