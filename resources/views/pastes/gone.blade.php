<x-layouts.app title="{{ __('safe_paste.gone.title') }}">
    <section class="mx-auto max-w-xl rounded-lg border border-white/10 bg-zinc-900/80 p-6 text-center shadow-2xl shadow-black/20">
        <p class="text-sm font-semibold uppercase tracking-wide text-amber-300">410 Gone</p>
        <h1 class="mt-2 text-2xl font-semibold text-white">{{ __('safe_paste.gone.heading') }}</h1>
        <p class="mt-3 text-sm text-zinc-400">
            @if ($reason === 'expired')
                {{ __('safe_paste.gone.expired') }}
            @elseif ($reason === 'burned')
                {{ __('safe_paste.gone.burned') }}
            @else
                {{ __('safe_paste.gone.view_limit') }}
            @endif
        </p>
        <a href="{{ route('home') }}" class="mt-6 inline-flex rounded-md bg-teal-500 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.gone.new') }}</a>
    </section>
</x-layouts.app>
