<?php

namespace App\Observers;

use App\Models\Api\Report;

class ReportApiReplicator
{
    public function created(Report $report): void
    {
        $data = $report->toArray();
        unset($data['id'],$data['fundbank'],$data['apicode'],$data['apiname'],$data['username'],$data['sendername'],$data['providername']);
        \App\Models\Report::insert($data);
    }
    public function updated(Report $report): void
    {
        $data = $report->toArray();
        unset($data['fundbank'],$data['apicode'],$data['apiname'],$data['username'],$data['sendername'],$data['providername']);
        \App\Models\report::where('id', $data['id'])->update($data);
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
