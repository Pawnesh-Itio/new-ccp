<?php

namespace App\Observers;

use App\Models\Scheme;

class SchemeReplicator
{
    public function created(Scheme $scheme): void
    {
        $data = $scheme->toArray();
        unset($data['id']);
        \App\Models\Api\Scheme::insert($data);
    }
    public function updated(Scheme $scheme): void
    {
        $data = $scheme->toArray();
        \App\Models\Api\Scheme::where('id', $data['id'])->update($data);
    }
    public function deleted(Scheme $scheme): void
    {
        //
    }
    public function restored(Scheme $scheme): void
    {
        //
    }
    public function forceDeleted(Scheme $scheme): void
    {
        //
    }
}
