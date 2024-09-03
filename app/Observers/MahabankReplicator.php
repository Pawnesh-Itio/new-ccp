<?php

namespace App\Observers;

use App\Models\Mahabank;

class MahabankReplicator
{
    public function created(Mahabank $mahabank): void
    {
        $data = $mahabank->toArray();
        unset($data['id']);
        \App\Models\Api\Mahabank::insert($data);
    }
    public function updated(Mahabank $mahabank): void
    {
        $data = $mahabank->toArray();
        \App\Models\Api\Mahabank::where("id", $data['id'])->update($data);
    }
    public function deleted(Mahabank $mahabank): void
    {
        //
    }
    public function restored(Mahabank $mahabank): void
    {
        //
    }
    public function forceDeleted(Mahabank $mahabank): void
    {
        //
    }
}
