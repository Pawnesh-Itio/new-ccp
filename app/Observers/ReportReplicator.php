<?php

namespace App\Observers;

use App\Models\Report;

class ReportReplicator
{
    public function created(Report $report): void
    {
        $data = $report->toArray();
        unset($data['id'],$data['fundbank'],$data['apicode'],$data['apiname'],$data['username'],$data['sendername'],$data['providername']);
        \App\Models\Api\Report::insert($data);
    }
    public function updated(Report $report): void
    {
        $data = $report->toArray();
        \App\Models\Api\report::where('id', $data['id'])->update($data);
    }
    public function deleted(Report $report): void
    {
        //
    }
    public function restored(Report $report): void
    {
        //
    }
    public function forceDeleted(Report $report): void
    {
        //
    }
}