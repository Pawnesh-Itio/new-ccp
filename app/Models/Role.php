<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    protected static $logAttributes = ['name', 'slug'];
    protected static $logOnlyDirty = true;
    public $appends = ['scheme']; 
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }
	public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }

    public function getSchemeAttribute($value)
    {
        $scheme  = \DB::table('default_permissions')->where('type', 'scheme')->where('role_id', $this->id)->first();
        if($scheme){
            return $scheme->permission_id;
        }else{
            return "";
        }
    }
}
