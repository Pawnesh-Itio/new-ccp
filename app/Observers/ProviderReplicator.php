<?php

namespace App\Observers;

use App\Models\Provider;

class ProviderReplicator
{
    public function created(Provider $provider): void
    {
        $data = $provider->toArray();
        unset($data['id']);
        \App\Models\Api\Provider::insert($data);
    }
    public function updated(Provider $provider): void
    {
        $data = $provider->toArray();
        \App\Models\Api\Provider::where('id', $data['id'])->update($data);
    }
    public function deleted(Provider $provider): void
    {
        //
    }
    public function restored(Provider $provider): void
    {
        //
    }
    public function forceDeleted(Provider $provider): void
    {
        //
    }
}
