<?php

namespace App\Observers;

use App\Models\PortalSetting;

class PortalSettingReplicator
{
    public function created(PortalSetting $portalSetting): void
    {
        $data = $portalSetting->toArray();
        unset($data['id']);
        \App\Models\Api\PortalSetting::insert($data);
    }
    public function updated(PortalSetting $portalSetting): void
    {
        $data = $portalSetting->toArray();
        \App\Models\Api\PortalSetting::where('id', $data['id'])->update($data);
    }
    public function deleted(PortalSetting $portalSetting): void
    {
        //
    }
    public function restored(PortalSetting $portalSetting): void
    {
        //
    }
    public function forceDeleted(PortalSetting $portalSetting): void
    {
        //
    }
}
