<?php

namespace App\Observers;

use App\Models\Beneficiarybank;

class BeneficiarybankReplicator
{
    public function created(Beneficiarybank $beneficiarybank): void
    {
        $data = $beneficiarybank->toArray();
        unset($data['id'],$data['username']);
        \App\Models\Api\Beneficiarybank::insert($data);
    }
    public function updated(Beneficiarybank $beneficiarybank): void
    {
        
    }
    public function deleted(Beneficiarybank $beneficiarybank): void
    {
        $data = $beneficiarybank->toArray();
        \App\Models\Api\Beneficiarybank::where('id', $data['id'])
                  ->where('user_id', \Auth::id())
                  ->delete();
    }
    public function restored(Beneficiarybank $beneficiarybank): void
    {
        //
    }
    public function forceDeleted(Beneficiarybank $beneficiarybank): void
    {
        //
    }
}
