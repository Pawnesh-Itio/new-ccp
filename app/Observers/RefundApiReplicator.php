<?php

namespace App\Observers;

use App\Models\Api\Refund;

class RefundApiReplicator
{
    public function created(Refund $refund): void
    {
        $data = $refund->toArray();
        unset($data['id']);
        \App\Models\Refund::insert($data);
    }
    public function updated(Refund $refund): void
    {
        $data = $refund->toArray();

        \App\Models\Refund::where('id', $data['id'])->update($data);
    }
    public function deleted(Refund $refund): void
    {
        //
    }
    public function restored(Refund $refund): void
    {
        //
    }
    public function forceDeleted(Refund $refund): void
    {
        //
    }
}
