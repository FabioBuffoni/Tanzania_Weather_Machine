<?php

namespace App\Services;

use App\Contracts\SmsService;
use App\Models\Subscriber;
use Twilio\Rest\Client;

class TwilioSmsService implements SmsService
{
    protected $client;
    protected $from;
    protected $messagingServiceSid;

    public function __construct(string $sid, string $token, ?string $from = null, ?string $messagingServiceSid = null)
    {
        if (empty($sid) || empty($token)) {
             // Avoid crashing when no creds (for dev/simulated environments)
             // We can check this flag before sending.
             $this->client = null;
        } else {
             $this->client = new Client($sid, $token);
        }

        $this->from = $from;
        $this->messagingServiceSid = $messagingServiceSid;
    }

    public function sendSms(string $to, string $message): array
    {
        if (!$this->client) {
             \Log::warning("Twilio credentials missing. SMS simulated to {$to}: {$message}");
             return ['status' => 'simulated'];
        }

        $params = [
            'body' => $message,
        ];

        if ($this->messagingServiceSid) {
            $params['messagingServiceSid'] = $this->messagingServiceSid;
        } elseif ($this->from) {
            $params['from'] = $this->from;
        } else {
            throw new \Exception('Twilio FROM number or Messaging Service SID must be configured.');
        }

        try {
            $messageInstance = $this->client->messages->create($to, $params);
            return [
                'sid' => $messageInstance->sid,
                'status' => $messageInstance->status,
                'error_code' => $messageInstance->errorCode,
                'error_message' => $messageInstance->errorMessage,
            ];
        } catch (\Exception $e) {
            // Log error
            \Log::error('Twilio SMS Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendForecast(Subscriber $subscriber, array $forecast): array
    {
        // Format the message
        $isChangeAlert = $forecast['is_change_alert'] ?? false;

        $emoji = $isChangeAlert ? '⚠️' : '🌤️';
        $title = $isChangeAlert ? 'Forecast Update' : 'Daily Forecast';

        $body = "{$emoji} {$title} for {$forecast['date']}:\n";
        $body .= "High: {$forecast['temp_high']}°C, Low: {$forecast['temp_low']}°C\n";
        $body .= "Rain: {$forecast['precip_chance']}%\n";
        $body .= "{$forecast['summary']}\n\n";

        if (!empty($subscriber->opt_out_token)) {
             // In a real app, this would be a link to a route like /sms/unsubscribe/{token}
             // For now, we instruct to reply STOP
             $body .= "Reply STOP to unsubscribe.";
        }

        return $this->sendSms($subscriber->phone_number, $body);
    }
}
