<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('safe_paste.brand') }}</title>
    @unless (app()->environment('testing'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endunless
</head>
<body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(20,184,166,0.18),_transparent_35%),linear-gradient(180deg,_#09090b,_#18181b)]">
        <header class="border-b border-white/10">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-white">{{ __('safe_paste.brand') }}</a>
                <div class="flex items-center gap-3 text-sm">
                    <form method="POST" action="{{ route('locale.update', app()->getLocale() === 'fa' ? 'en' : 'fa') }}">
                        @csrf
                        <button class="rounded-md border border-white/10 px-3 py-2 text-zinc-300 hover:bg-white/10 hover:text-white">
                            {{ app()->getLocale() === 'fa' ? __('safe_paste.language.en') : __('safe_paste.language.fa') }}
                        </button>
                    </form>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-zinc-300 hover:text-white">{{ __('safe_paste.nav.dashboard') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-zinc-300 hover:text-white">{{ __('safe_paste.nav.logout') }}</button>
                        </form>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-zinc-300 hover:text-white">{{ __('safe_paste.nav.login') }}</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-md bg-teal-500 px-3 py-2 font-medium text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.nav.register') }}</a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-teal-400/30 bg-teal-400/10 px-4 py-3 text-sm text-teal-100">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
