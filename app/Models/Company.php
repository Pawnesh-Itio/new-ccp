<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['companyname', 'website', 'status', 'type', 'logo', 'senderid', 'smsuser', 'smspwd','public_key','terno'];
}
