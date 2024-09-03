<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    protected $table = 'open_acquirer_capture_refunds';
    protected $fillable = ['report_id', 'reference_id', 'amount', 'type'];
}
