<?php

namespace App\Observers;

use App\Models\Apitoken;

class ApitokenReplicator
{
    public function created(Apitoken $apitoken): void
    {
        $data = $apitoken->toArray();
        unset($data['id']);
        \App\Models\Api\Apitoken::insert($data);
    }
    public function updated(Apitoken $apitoken): void
    {
        $data = $apitoken->toArray();
        \App\Models\Api\Apitoken::where('id', $data['id'])
                ->where('user_id', \Auth::id())
                ->update();
    }
    public function deleted(Apitoken $apitoken): void
    {
      $data = $apitoken->toArray();
      \App\Models\Api\Apitoken::where('id', $data['id'])
                ->where('user_id', \Auth::id())
                ->delete();
    }
    public function restored(Apitoken $apitoken): void
    {
        //
    }
    public function forceDeleted(Apitoken $apitoken): void
    {
        //
    }
}
