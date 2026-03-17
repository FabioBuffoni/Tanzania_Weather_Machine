<!DOCTYPE html>
<html class="scroll-smooth pe-0! overflow-auto!" lang="{{ config('app.locale', 'en') }}">
<head>
    @include('partials.head')
</head>
<body>

<x-navbar/>

<div>

    {{ $slot }}

</div>

@fluxScripts
@livewireScripts
</body>
</html>
