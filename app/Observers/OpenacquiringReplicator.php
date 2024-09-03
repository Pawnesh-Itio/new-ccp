<?php

namespace App\Observers;

use App\Models\Openacquiring;

class OpenacquiringReplicator
{

    public function created(Openacquiring $openacquiring): void
    {
        $data = $openacquiring->toArray();
        unset($data['id']);
        \App\Models\Api\Openacquiring::insert($data);
    }
    public function updated(Openacquiring $openacquiring): void
    {
        $data = $openacquiring->toArray();
       \App\Models\Api\Openacquiring::where('id', $data['id'])->update($data);
    }
    public function deleted(Openacquiring $openacquiring): void
    {
        //
    }
    public function restored(Openacquiring $openacquiring): void
    {
        //
    }
    public function forceDeleted(Openacquiring $openacquiring): void
    {
        //
    }
}
