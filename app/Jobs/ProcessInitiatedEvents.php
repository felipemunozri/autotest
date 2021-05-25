<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class ProcessInitiatedEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::debug('Start - old initiated event process');
        Event::query()
            ->whereHas('status', function ($q) {
                $q->where('code', 'created');
            })
            ->where('updated_at','<', now()->subDay(1))
            ->each(function ($oldRecord) {
                $archivedRecord = $oldRecord->replicate();
                $archivedRecord->setTable('initiated_events');
                $archivedRecord->save();
                $oldRecord->delete();
            });
        \Log::debug('Finish - old initiated event process');
    }
}
