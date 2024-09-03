<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companydata extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'description', 'type', 'company_id'];
    public $timestamps = false;
}
