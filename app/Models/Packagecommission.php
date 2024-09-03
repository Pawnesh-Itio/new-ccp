<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packagecommission extends Model
{
    use HasFactory;
    protected $fillable = ['slab', 'type', 'value', 'scheme_id'];

    public $with = ['provider'];

    public function provider(){
        return $this->belongsTo('App\Model\Provider', 'slab');
    }
}
