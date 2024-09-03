<?php

namespace App\Observers;

use App\Models\Commission;

class CommissionReplicator
{
    public function created(Commission $commission): void
    {
        $data = $commission->toArray();
        unset($data['id']);
        \App\Models\Api\Commission::insert($data);
    }
    public function updated(Commission $commission): void
    {
        $data = $commission->toArray();
        $commArr['type']=  $data['type'];
        $commArr['apiuser'] = $data['apiuser'];

        \App\Models\Api\Commission::where('id', $data['id'])->update($commArr);
    }
    public function deleted(Commission $commission): void
    {
        //
    }
    public function restored(Commission $commission): void
    {
        //
    }

    /**
     * Handle the Commission "force deleted" event.
     */
    public function forceDeleted(Commission $commission): void
    {
        //
    }
}
