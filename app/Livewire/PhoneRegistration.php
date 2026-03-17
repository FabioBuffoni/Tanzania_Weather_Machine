<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Subscriber;

#[Layout('components.layouts.default')]
class PhoneRegistration extends Component
{
    public $name = '';
    public $phone = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:2|max:255',
            'phone' => 'required|string|min:8|max:20|unique:subscribers,phone_number',
        ], [
            'phone.unique' => 'This phone number is already registered.',
            'name.required' => 'Please enter your name.',
            'phone.required' => 'Please enter your phone number.',
        ]);

        // 2. Sla op in de database
        Subscriber::create([
            'name' => $this->name,
            'phone_number' => $this->phone,
        ]);

        $this->reset(['name', 'phone']);

        session()->flash('success', 'Success! Your phone number has been registered for Early Warning alerts.');
    }

    public function render()
    {
        return view('livewire.phone-registration');
    }
}
