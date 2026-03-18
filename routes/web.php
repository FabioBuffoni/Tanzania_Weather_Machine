<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Home;
use App\Livewire\PhoneRegistration;
use App\Livewire\WeatherPage;

//Route::get('/', function () {
//    return view('welcome');
//})->name('home');

Route::get('/', Home::class ) -> name('home');

Route::get('phone-registration', PhoneRegistration::class ) -> name('phone-registration');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get("weather", WeatherPage::class) -> name('weather');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';

use Twilio\Rest\Client;

Route::get('/send-weather', function () {

    // --- 1. JOUW WEERBERICHT ---
    $city = 'Geel';
    $temp = 22;
    $feels = 20;
    $desc = 'zonnig';
    $humidity = 40;
    $wind = 2.5;

    $text = "Weerupdate {$city}: {$desc}, {$temp}°C (gevoel {$feels}°C), vocht {$humidity}%, wind {$wind} m/s";


    // --- 2. Twilio client ---
    $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
    $from = env('TWILIO_FROM');
    $to   = env('SINGLE_SMS_TO');   // <-- komt uit je .env


    // --- 3. SMS versturen ---
    $twilio->messages->create($to, [
        'from' => $from,
        'body' => $text,
    ]);

    // --- 4. TERUGGAVE ---
    return "SMS verzonden naar {$to}";
});

Route::get('/ping', function () {
    return 'pong';
});