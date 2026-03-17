<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Weer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen font-sans antialiased text-zinc-900">

{{-- HERO --}}
<section class="flex flex-col items-center justify-center text-center px-4 py-28">
    <h1 class="text-6xl font-bold text-zinc-900 tracking-tight leading-tight max-w-lg">
        Weekoverzicht
    </h1>
    <p class="mt-5 text-zinc-500 text-lg max-w-md leading-relaxed">
        Temperatuur en neerslag voor de komende 7 dagen in Geel, België.
    </p>
    <div class="mt-4">
        <flux:badge color="green">17 Maart 2026 · Bijgewerkt om 14:30</flux:badge>
    </div>
</section>

{{-- DIVIDER --}}
<div class="border-t border-zinc-100"></div>

{{-- WEEKOVERZICHT --}}
<section class="bg-emerald-50/60 px-4 py-16">
    <div class="max-w-xl mx-auto">

        <p class="text-emerald-600 text-sm font-semibold uppercase tracking-widest text-center mb-2">Voorspelling</p>
        <h2 class="text-zinc-900 text-3xl font-bold text-center mb-10">Deze week</h2>

        <div class="space-y-1">

            {{-- Vandaag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Vandaag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">20%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">8°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:55%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">14°</span>
                </div>
            </div>

            {{-- Woensdag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Woensdag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">10%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">7°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:55%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">14°</span>
                </div>
            </div>

            {{-- Donderdag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Donderdag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">10%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">9°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:75%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">16°</span>
                </div>
            </div>

            {{-- Vrijdag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Vrijdag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">80%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">8°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:45%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">13°</span>
                </div>
            </div>

            {{-- Zaterdag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Zaterdag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">40%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">7°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:45%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">13°</span>
                </div>
            </div>

            {{-- Zondag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Zondag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">5%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">6°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:60%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">15°</span>
                </div>
            </div>

            {{-- Maandag --}}
            <div class="flex items-center justify-between py-3 px-4 rounded-xl hover:bg-white transition-colors">
                <span class="text-zinc-900 text-sm font-semibold w-24">Maandag</span>
                <div class="flex items-center gap-1 w-20">
                    <span class="text-emerald-500 text-xs">💧</span>
                    <span class="text-emerald-600 text-sm font-mono">25%</span>
                </div>
                <div class="flex items-center gap-2 w-32 justify-end">
                    <span class="text-zinc-400 text-sm font-mono">7°</span>
                    <div class="w-16 h-1 bg-emerald-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width:50%"></div>
                    </div>
                    <span class="text-zinc-900 text-sm font-mono font-semibold">13°</span>
                </div>
            </div>

        </div>
    </div>
</section>
</body>
</html>
