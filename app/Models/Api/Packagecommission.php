<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packagecommission extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['slab', 'type', 'value', 'scheme_id'];

    public $with = ['provider'];

    public function provider(){
        return $this->belongsTo('App\Model\Provider', 'slab');
    }
}
