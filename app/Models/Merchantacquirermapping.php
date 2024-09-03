<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchantacquirermapping extends Model
{
    use HasFactory;
    protected $primaryKey = 'merchant_acquirer_mapping_id';
    protected $table = 'merchant_acquirer_mapping';
    protected $fillable = ['merchant_id', 'acquirer_id'];
    public $timestamps = false;
}
