<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendTelegramAlertJob;

class StoreActivityLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->onQueue('activity'); // 👈 pisahkan queue
    }

    public function handle(): void
    {
        $log = ActivityLog::create($this->data);

        if (($this->data['severity'] ?? 'info') === 'critical') {
            SendTelegramAlertJob::dispatch($log->id)
                ->onQueue('alerts');
        }
    }
}