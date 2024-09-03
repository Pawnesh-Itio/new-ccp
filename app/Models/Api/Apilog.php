<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apilog extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_second';
    protected $fillable = ['url','modal', 'txnid', 'header', 'request', 'response'];

    public function setHeaderAttribute($value)
    {
        $this->attributes['header'] = urlencode(json_encode($value));
    }

    public function setRequestAttribute($value)
    {
        $this->attributes['request'] = urlencode(json_encode($value));
    }

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = urlencode(json_encode($value));
    }

    public function getHeaderAttribute($value)
    {
        return urldecode($value);
    }

    public function getRequestAttribute($value)
    {
        return urldecode($value);
    }

    public function getResponseAttribute($value)
    {
        return urldecode($value);
    }
}
