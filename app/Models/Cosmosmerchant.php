<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cosmosmerchant extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','mid','pgmerchentId','complianceStatus','merchentLegalName','businessName','locationCountry','Address','City','State','postalCode','categoryofMerchant','Purpose','merchantIntegrationApproach','panNo','mebusinessType','mcc','settlementType','perDaytransactionCnt','perdaytransactionlimit','pertransactionLimit','whitelistedURL','externalMID','externalTID','gstn','merchantType','merchantGenre','onboardingType','mobileNumber','WebApp','WebURL','vpa','sid','created_at','updated_at'];
    
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public $appends = ['username'];
    
    public function getUsernameAttribute()
    {
        $data = '';
        if($this->user_id){
            $user = \App\Models\User::where('id' , $this->user_id)->first(['name', 'id', 'role_id']);
            $data = $user->name." (".$user->id.") <br>(".$user->role->name.")";
        }
        return $data;
    }
}
