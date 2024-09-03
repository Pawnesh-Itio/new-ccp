<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apitoken extends Model
{
    use HasFactory;
    protected $fillable = ['token', 'ip', 'user_id', 'domain'];

    public function setDomainAttribute($value)
    {
        $this->attributes['domain'] = str_replace("https://", '', str_replace("http://", '', str_replace("www.", '', $value)));
    }
}
