<?php

namespace App\Observers;

use App\Models\Packagecommission;

class PackagecommissionReplicator
{
    public function created(Packagecommission $packagecommission): void
    {
        $data = $packagecommission->toArray();
        unset($data['id']);
        \App\Models\Api\Packagecommission::insert($data);
    }
    public function updated(Packagecommission $packagecommission): void
    {
        $data = $packagecommission->toArray();
        \App\Models\Api\Packagecommission::where("id",$data['id'])->update($data);
    }
    public function deleted(Packagecommission $packagecommission): void
    {
        //
    }
    public function restored(Packagecommission $packagecommission): void
    {
        //
    }
    public function forceDeleted(Packagecommission $packagecommission): void
    {
        //
    }
}
