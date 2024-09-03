<?php

namespace App\Observers;

use App\Models\Apilog;

class ApilogsReplicator
{
    public function created(Apilog $apilog): void
    {
        $data = $apilog->toArray();
        unset($data['id']);
        \App\Models\Api\Apilog::insert($data);
    }
    public function updated(Apilog $apilog): void
    {
        $data = $apilog->toArray();
        \App\Models\Api\Apilog::where('id', $data['id'])->update($data);
    }
    public function deleted(Apilog $apilog): void
    {
        //
    }
    public function restored(Apilog $apilog): void
    {
        //
    }
    public function forceDeleted(Apilog $apilog): void
    {
        //
    }
}
