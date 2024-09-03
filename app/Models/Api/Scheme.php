<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['name', 'user_id', 'status'];

    protected static $logAttributes = ['name', 'user_id', 'status'];
    protected static $logOnlyDirty = true;

    public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }
}
