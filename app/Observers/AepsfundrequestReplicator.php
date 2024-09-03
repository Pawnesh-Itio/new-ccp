<?php

namespace App\Observers;

use App\Models\Aepsfundrequest;
 
class AepsfundrequestReplicator
{
    public function created(Aepsfundrequest $aepsfundrequest): void
    {
        $data = $aepsfundrequest->toArray();
        \App\Models\Api\Apifundrequest::insert($data);
    }
    public function updated(Aepsfundrequest $aepsfundrequest): void
    {
        $data = $aepsfundrequest->toArray();
        unset($data['id']);
        \App\Models\Api\Aepsfundrequest::where('id', $data['id'])->update($data);
    }
    public function deleted(Aepsfundrequest $aepsfundrequest): void
    {
        //
    }
    public function restored(Aepsfundrequest $aepsfundrequest): void
    {
        //
    }
    public function forceDeleted(Aepsfundrequest $aepsfundrequest): void
    {
        //
    }
}
