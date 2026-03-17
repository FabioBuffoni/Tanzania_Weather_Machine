<?php

namespace App\Console\Commands;

use App\Jobs\SendForecastSms;
use App\Models\Subscriber;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DetectAndSendForecastChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:sms-detect-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for significant weather changes and notify subscribers';

    /**
     * Execute the console command.
     */
    public function handle(WeatherService $weatherService)
    {
        // 1. Get latest forecast
        $today = Carbon::today();
        try {
            $currentForecast = $weatherService->getForecastForDate($today);
        } catch (\Exception $e) {
            $this->error('Failed to get forecast: ' . $e->getMessage());
            return 1;
        }

        // 2. Iterate subscribers who have already received a forecast today
        //    (Only alert those who got an earlier version)
        $subscribers = Subscriber::where('phone_opt_in', true)
            ->whereNotNull('last_sent_at')
            ->whereDate('last_sent_at', $today)
            ->get();

        $this->info("Checking changes for {$subscribers->count()} subscribers.");

        $count = 0;
        foreach ($subscribers as $sub) {
            $lastForecast = $sub->last_sent_forecast;

            // Check if significant
            if ($weatherService->isSignificantChange($lastForecast, $currentForecast)) {

                // Avoid sending multiple change alerts in one day?
                // Check if last sent was already an alert (optional logic)

                $this->info("Significant change detected for subscriber {$sub->id}. Sending alert.");

                SendForecastSms::dispatch($sub, $currentForecast, true); // true = IS change alert
                $count++;
            }
        }

        $this->info("Dispatched {$count} change alert jobs.");
    }
}

