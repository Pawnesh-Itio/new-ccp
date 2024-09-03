<?php

namespace App\Observers;

use App\Models\Api\Userdata;

class UserdataApiReplicator
{
    public function created(Userdata $userdata): void
    {
        //
    }
    public function updated(Userdata $userdata): void
    {
        $data = $userdata->toArray();
        $user_id = $data['user_id'];
        $updateData = array(
            "mainwallet"=> $data['mainwallet']
        );
        \App\Models\User::where("id",$user_id)->update($updateData);
    }
    public function deleted(Userdata $userdata): void
    {
        //
    }
    public function restored(Userdata $userdata): void
    {
        //
    }
    public function forceDeleted(Userdata $userdata): void
    {
        //
    }
}
