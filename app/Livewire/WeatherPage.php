<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;

use Livewire\Component;

#(Layout('components.layouts.default'))
class WeatherPage extends Component
{
    public function render()
    {
        return view('livewire.weather-page');
    }
}
