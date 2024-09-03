<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalSetting extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['name', 'code', 'value'];
    public $timestamps = false;
}
