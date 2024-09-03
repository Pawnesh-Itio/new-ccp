<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['slab', 'type', 'apiuser', 'whitelable', 'md', 'distributor', 'retailer', 'scheme_id'];
}
