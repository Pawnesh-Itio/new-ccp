<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aepsreport extends Model
{
    use HasFactory;
    protected $fillable = ['mobile' ,'aadhar','payeeVPA','payerMobile','payerAccName','payerIFSC','payerAccNo','payer_vpa', 'txnid', 'api_id', 'amount', 'charge', 'bank', 'payid', 'refno', 'mytxnid', 'terminalid', 'authcode', 'balance', 'status', 'type', 'remark', 'rtype', 'transtype', 'aepstype', 'TxnMedium', 'user_id', 'credited_by','wid','wprofit','mdid','mdprofit','disid','disprofit','oid','oprofit', 'provider_id'];

    protected static $logAttributes = ['mobile' ,'aadhar','payerMobile','payerAccName','payerIFSC','payerAccNo','payeeVPA','payer_vpa', 'txnid', 'api_id', 'amount', 'charge', 'bank', 'payid', 'refno', 'mytxnid', 'terminalid', 'authcode', 'balance', 'status', 'type', 'remark', 'rtype', 'transtype', 'aepstype', 'TxnMedium', 'user_id', 'credited_by','wid','wprofit','mdid','mdprofit','disid','disprofit', 'provider_id'];

    protected static $logOnlyDirty = true;
    
    public $appends = ['apicode', 'username'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function api(){
        return $this->belongsTo('App\Models\Api');
    }

    public function getApicodeAttribute()
    {
        $data = Api::where('id' , $this->api_id)->first(['code']);
        if($data){
            return $data->code;
        }else{
            return '';
        }
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
}
