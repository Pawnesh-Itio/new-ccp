<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name','mainwallet','email','mobile','gstrate','gender','qrdata','password','passwordold','remember_token','nsdlwallet','lockedamount','role_id','parent_id','company_id','scheme_id','status','address','shopname','gstin','city','state','pincode','pancard','aadharcard','pancardpic','aadharcardpic','gstpic','profile','kyc','callbackurl','remark','resetpwd','otpverify','otpresend','account','bank','ifsc','contact_id1','contact_id2','contact_id3','account2','bank2','ifsc2','account3','bank3','ifsc3','sdktoken','apptoken','s2s_agent','country','currancy_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected static $logAttributes = ['id','name','email','mobile','gender','password','scheme_id','status','address','shopname','gstin','city','state','pincode','pancard','aadharcard','callbackurl','otpverify','otpresend','account','bank','ifsc','apptoken'];

    protected static $logOnlyDirty = true;

    public $with = ['role', 'company'];
    protected $appends = ['parents'];

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }
    
    public function company(){
        return $this->belongsTo('App\Models\Company');
    }

    public function getParentsAttribute() {
        $user = User::where('id', $this->parent_id)->first(['id', 'name', 'mobile', 'role_id']);
        if($user){
            return $user->name." (".$user->id.")<br>".$user->mobile."<br>".$user->role->name;
        }else{
            return "Not Found";
        }
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d M y - h:i A', strtotime($value));
    }
    
}
