<?php

namespace App\Jobs;

use App\Contracts\SmsService;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendForecastSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscriber;
    public $forecast;
    public $isChangeAlert;

    /**
     * Create a new job instance.
     */
    public function __construct(Subscriber $subscriber, array $forecast, bool $isChangeAlert = false)
    {
        $this->subscriber = $subscriber;
        $this->forecast = $forecast;
        $this->isChangeAlert = $isChangeAlert;

        $this->onQueue('sms');
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        // Don't send if opted out (double check)
        if (!$this->subscriber->phone_opt_in) {
            return;
        }

        try {
            // Add is_change_alert to forecast so service can format appropriately
            $this->forecast['is_change_alert'] = $this->isChangeAlert;
            $this->forecast['source_ts'] = now()->timestamp; // ensure fresh timestamp

            $smsService->sendForecast($this->subscriber, $this->forecast);

            // Update database to track last success
            $this->subscriber->update([
                'last_sent_forecast' => $this->forecast,
                'last_sent_at' => now(),
            ]);

            Log::info("SMS forecast sent to Subscriber {$this->subscriber->id}");

        } catch (\Exception $e) {
            Log::error("Failed to send SMS to Subscriber {$this->subscriber->id}: " . $e->getMessage());

            // Optionally release back to queue if it's a temporary error
            // $this->release(60);
            throw $e;
        }
    }
}

