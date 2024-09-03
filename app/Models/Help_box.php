<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Help_box extends Model
{
    use HasFactory;
    protected $table = 'help_box';
    protected $fillable = ['description', 'slug','type'];
}
