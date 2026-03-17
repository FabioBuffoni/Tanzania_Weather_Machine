<?php

namespace App\Contracts;

use App\Models\Subscriber;

interface SmsService
{
    public function sendSms(string $to, string $message): array;
    public function sendForecast(Subscriber $subscriber, array $forecast): array;
}

