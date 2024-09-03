<?php

namespace App\Observers;

use App\Models\Package;

class PackageReplicator
{
    public function created(Package $package): void
    {
        $data = $package->toArray();
        unset($data['id']);
        \App\Models\Api\Package::insert($data);
    }
    public function updated(Package $package): void
    {
        $data = $package->toArray();
        \App\Models\Api\Package::where("id",$data['id'])->update($data);
    }
    public function deleted(Package $package): void
    {
        //
    }
    public function restored(Package $package): void
    {
        //
    }
    public function forceDeleted(Package $package): void
    {
        //
    }
}
