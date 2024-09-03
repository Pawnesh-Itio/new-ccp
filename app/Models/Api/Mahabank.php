<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahabank extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['bankid','bankcode','bankname','masterifsc','url'];
}
