<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalSetting extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'value'];
    public $timestamps = false;
}
