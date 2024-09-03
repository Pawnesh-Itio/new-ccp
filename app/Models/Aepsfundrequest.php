<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aepsfundrequest extends Model
{
    use HasFactory;
    protected $fillable = ['amount','name', 'remark', 'status', 'type', 'user_id','account','contact_id', 'bank', 'ifsc', 'pay_type', 'payoutid','payoutref'];

    protected static $logAttributes = ['amount','name', 'remark', 'status', 'type', 'user_id','contact_id','account', 'bank', 'ifsc', 'pay_type', 'payoutid','payoutref'];

    protected static $logOnlyDirty = true;
    
    public $appends = ['username'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\Models\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") (".$user->role->name.")";
        }
        return $data;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y - h:i:s A', strtotime($value));
    }
}
