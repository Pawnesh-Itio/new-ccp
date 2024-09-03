<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acquirerfield extends Model
{
    use HasFactory;
    // 
    protected $primaryKey = 'field_id';
    protected $fillable = ['acquirer_id', 'field_name', 'field_label', 'field_type', 'is_active'];
    public $timestamps = false;
}
