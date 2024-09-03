<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userdata extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    public $timestamps = false;
    protected $connection = 'pgsql_second';
    protected $table = 'userdata';
    protected $fillable = ['user_id','name','mobile','role_slug','scheme_id','mainwallet'];
    
}
