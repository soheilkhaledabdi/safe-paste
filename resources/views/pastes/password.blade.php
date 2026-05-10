<x-layouts.app title="{{ __('safe_paste.paste.password_required') }}">
    <section class="mx-auto max-w-md rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/20">
        <h1 class="text-2xl font-semibold text-white">{{ __('safe_paste.paste.password_required') }}</h1>
        <p class="mt-2 text-sm text-zinc-400">{{ __('safe_paste.paste.password_help') }}</p>

        <form method="POST" action="{{ route('pastes.password.verify', $paste->slug) }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.password') }}</label>
                <input id="password" name="password" type="password" autofocus class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                @error('password') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
            </div>

            <button class="w-full rounded-md bg-teal-500 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.paste.unlock') }}</button>
        </form>
    </section>
</x-layouts.app>
