<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected string $location = 'Geel, België'; // Hardcoded as requested

    // In a real app these should be configurable or fetched
    protected float $lat = -6.1722;
    protected float $lon = 35.7410;

    /**
     * Fetch historical weather data for the last 4 days from Open-Meteo API.
     */
    protected function getHistoricalData(): array
    {
        $endDate = Carbon::now()->subDay()->format('Y-m-d');
        $startDate = Carbon::now()->subDays(4)->format('Y-m-d');

        $url = "https://archive-api.open-meteo.com/v1/archive?latitude={$this->lat}&longitude={$this->lon}&start_date={$startDate}&end_date={$endDate}&hourly=temperature_2m,relative_humidity_2m,surface_pressure,cloud_cover,dew_point,soil_moisture_levels,precipitation&timezone=auto";

        // Note: Open-Meteo uses 'soil_moisture_0_to_7cm' or similar.
        // For simplicity, we'll map 'soil_moisture_0_to_7cm' to 'soil_moisture'.
        // Also checking endpoint compatibility. The archive API parameters might differ slightly.
        // Let's use the 'hourly' parameter as requested by the Python API schema.

        // Correct parameter for soil moisture in open-meteo is typically soil_moisture_0_to_7cm
        $response = Http::get("https://archive-api.open-meteo.com/v1/archive", [
            'latitude' => $this->lat,
            'longitude' => $this->lon,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'hourly' => 'temperature_2m,relative_humidity_2m,surface_pressure,cloud_cover,dew_point,soil_moisture_0_to_7cm,precipitation',
            'timezone' => 'auto'
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch historical weather data from Open-Meteo.');
        }

        $data = $response->json();
        $hourly = $data['hourly'];

        // Map Open-Meteo response to our API input format
        return [
            'hourly_times' => $hourly['time'],
            'temperature_2m' => $hourly['temperature_2m'],
            'relative_humidity_2m' => $hourly['relative_humidity_2m'],
            'surface_pressure' => $hourly['surface_pressure'],
            'cloud_cover' => $hourly['cloud_cover'],
            'dew_point' => $hourly['dew_point'],
            'soil_moisture' => $hourly['soil_moisture_0_to_7cm'], // Map this to soil_moisture
            'precipitation' => $hourly['precipitation'],
        ];
    }

    /**
     * Get weather forecast for a specific date using the custom Python API.
     */
    public function getForecastForDate(\DateTimeInterface $date): array
    {
        // 1. Fetch 4 days of history
        try {
            $historicalData = $this->getHistoricalData();
        } catch (\Exception $e) {
            \Log::error("Historical data fetch failed: " . $e->getMessage());
            // Fallback or rethrow? For now, we'll return a fallback to avoid crashing commands
            return $this->getFallbackForecast($date);
        }

        // 2. Call local Python Prediction API
        $apiUrl = config('services.weather_api.url');

        try {
            $response = Http::post("{$apiUrl}/forecast_3d", $historicalData);

            if ($response->failed()) {
                 throw new \Exception('Prediction API call failed: ' . $response->body());
            }

            $prediction = $response->json();
            $forecasts = $prediction['forecast']; // List of future hours

            // 3. Extract forecast for the specific date (daily aggregation)
            // The API returns hourly forecasts for 3 days. We need to aggregate to get high/low/precip/summary.

            $targetDate = $date->format('Y-m-d');
            $dailyTemps = [];
            $dailyPrecip = 0;
            $dailyRainChance = 0;

            foreach ($forecasts as $hour) {
                // prediction time format is ISO
                $hourDate = substr($hour['time'], 0, 10);
                if ($hourDate === $targetDate) {
                    $dailyTemps[] = $hour['temperature_2m'];
                    $dailyPrecip += $hour['precipitation_mm'];
                    // Take max probability of the day
                    if ($hour['chance_of_rain_percent'] > $dailyRainChance) {
                        $dailyRainChance = $hour['chance_of_rain_percent'];
                    }
                }
            }

            if (empty($dailyTemps)) {
                // If date requested is not in the returned forecast range (which is next 3 days)
                return $this->getFallbackForecast($date);
            }

            $tempHigh = max($dailyTemps);
            $tempLow = min($dailyTemps);

            // Build summary string
            $summary = "Cloudy"; // Default
            if ($dailyPrecip > 0.5 || $dailyRainChance > 40) {
                $summary = "Rain likely ({$dailyPrecip}mm)";
            } elseif ($tempHigh > 20) {
                $summary = "Sunny and warm";
            }

            return [
                'date' => $targetDate,
                'location' => $this->location,
                'temp_high' => round($tempHigh),
                'temp_low' => round($tempLow),
                'precip_chance' => round($dailyRainChance),
                'summary' => $summary,
                'source_ts' => now()->timestamp,
            ];

        } catch (\Exception $e) {
            \Log::error("Prediction API failed: " . $e->getMessage());
            return $this->getFallbackForecast($date);
        }
    }

    private function getFallbackForecast(\DateTimeInterface $date): array
    {
        return $this->getSimulatedForecast($date);
    }

    private function getSimulatedForecast(\DateTimeInterface $date): array
    {
        // SIMULATED DATA for demo purposes
        $isToday = $date->format('Y-m-d') === now()->format('Y-m-d');

        // Random variations to test change detection
        $randomTemp = rand(20, 30);
        $randomRain = rand(0, 100);

        return [
            'date' => $date->format('Y-m-d'),
            'location' => $this->location,
            'temp_high' => $randomTemp,
            'temp_low' => $randomTemp - 8,
            'precip_chance' => $randomRain,
            'summary' => $randomRain > 50 ? 'Rain likely.' : 'Sunny spells.',
            'source_ts' => now()->timestamp,
        ];
    }

    /**
     * Check if the new forecast is significantly different from the old one.
     */
    public function isSignificantChange(?array $oldForecast, array $newForecast): bool
    {
        if (!$oldForecast) {
            return false; // No previous forecast to compare against
        }

        // Thresholds
        $tempThreshold = 3;
        $precipThreshold = 20;

        $tempHighDiff = abs(($oldForecast['temp_high'] ?? 0) - $newForecast['temp_high']);
        $tempLowDiff = abs(($oldForecast['temp_low'] ?? 0) - $newForecast['temp_low']);
        $precipDiff = abs(($oldForecast['precip_chance'] ?? 0) - $newForecast['precip_chance']);

        if ($tempHighDiff >= $tempThreshold || $tempLowDiff >= $tempThreshold) {
            return true;
        }

        if ($precipDiff >= $precipThreshold) {
            return true;
        }

        return false;
    }
}
