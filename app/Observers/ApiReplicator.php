<?php

namespace App\Observers;

use App\Models\Api;

class ApiReplicator
{
    public function created(Api $api): void
    {
        $data = $api->toArray();
        unset($data['id']);
        \App\Models\Api\Api::insert($data);
    }
    public function updated(Api $api): void
    {
        $data = $api->toArray();
       \App\Models\Api\Api::where('id', $data['id'])->update($data);
    }
    public function deleted(Api $api): void
    {
        //
    }
    public function restored(Api $api): void
    {
        //
    }
    public function forceDeleted(Api $api): void
    {
        //
    }
}
