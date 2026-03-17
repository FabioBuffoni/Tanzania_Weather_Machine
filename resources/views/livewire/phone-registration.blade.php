<div class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-2xl text-center px-6 lg:px-8">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
            Sign up for Early Warning
        </h2>
        <p class="mt-4 text-lg leading-8 text-gray-600">
            Leave your name and phone number. We'll send you an SMS directly as soon as significant weather changes are on the way.
        </p>

        <form wire:submit="save" class="mt-10 mx-auto max-w-md bg-green-50 p-8 rounded-xl shadow-sm border border-green-100 relative">

            @if (session()->has('success'))
                <div class="mb-6 rounded-md bg-green-100 p-4 border border-green-400">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-left mb-6">
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                <div class="mt-2">
                    <input type="text" wire:model="name" id="name" placeholder="E.g. John Doe" class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                </div>
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="text-left mb-8">
                <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone number</label>
                <div class="mt-2">
                    <input type="tel" wire:model="phone" id="phone" placeholder="+255 123 456 789" class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-green-600 sm:text-sm sm:leading-6">
                </div>
                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="flex w-full justify-center rounded-md bg-green-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                Subscribe to SMS alerts
            </button>
        </form>
    </div>
</div>
