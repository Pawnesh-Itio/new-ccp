<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acquirer extends Model
{
    use HasFactory;
    protected $primaryKey = 'acquirer_id';
    protected $fillable = ['acquirer_name','acquirer_slug', 'api_endpoint', 'fields', 'is_active'];
    public $timestamps = false;
}
