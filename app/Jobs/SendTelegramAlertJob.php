<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTelegramAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $logId;

    public function __construct(int $logId)
    {
        $this->logId = $logId;
        $this->onQueue('alerts');
    }

    public function handle(): void
    {
        $log = ActivityLog::with('user')->find($this->logId);

        if (! $log) {
            return;
        }

        Http::timeout(10)->post(
            'https://api.telegram.org/bot' . config('services.telegram.token') . '/sendMessage',
            [
                'chat_id' => config('services.telegram.chat_id'),
                'parse_mode' => 'Markdown',
                'text' =>
                    "🚨 *CRITICAL ACTIVITY SIMOPKL*\n\n" .
                    "👤 User ID: {$log->user_id}\n" .
                    "👥 Name: {$userName}\n" .
                    "⚡ Action: {$log->action}\n" .
                    "📦 Module: {$log->module}\n" .
                    "📝 Desc: {$log->description}\n" .
                    "🌐 IP: {$log->ip_address}\n" .
                    "🕒 Time: {$log->created_at}",
            ]
        );
    }
}
