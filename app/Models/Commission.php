<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $fillable = ['slab', 'type', 'apiuser', 'whitelable', 'md', 'distributor', 'retailer', 'scheme_id'];

    protected static $logAttributes = ['slab', 'type', 'whitelable', 'md', 'distributor', 'retailer', 'scheme_id'];
    protected static $logOnlyDirty = true;
    
    public $with = ['provider'];

    public function provider(){
        return $this->belongsTo('App\Models\Provider', 'slab');
    }
}
