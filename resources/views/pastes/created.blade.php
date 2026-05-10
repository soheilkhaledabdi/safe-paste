<x-layouts.app title="{{ __('safe_paste.created.title') }}">
    <section class="mx-auto max-w-2xl rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/20">
        <h1 class="text-2xl font-semibold text-white">{{ __('safe_paste.created.heading') }}</h1>
        <p class="mt-2 text-sm text-zinc-400">{{ __('safe_paste.created.subtitle') }}</p>

        <div class="mt-6">
            <label for="paste-url" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.created.link') }}</label>
            <div class="mt-2 flex gap-2">
                <input id="paste-url" readonly value="{{ $pasteUrl }}" class="min-w-0 flex-1 rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white">
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('paste-url').value)" class="rounded-md bg-teal-500 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.common.copy') }}</button>
            </div>
        </div>

        @if ($paste->delete_token)
            <div class="mt-5 rounded-md border border-amber-300/20 bg-amber-300/10 p-4">
                <p class="text-sm text-amber-100">{{ __('safe_paste.created.guest_delete_token') }}</p>
                <form method="POST" action="{{ route('guest-pastes.destroy', [$paste->slug, $paste->delete_token]) }}" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-md border border-amber-300/30 px-3 py-2 text-sm font-medium text-amber-100 hover:bg-amber-300/10">{{ __('safe_paste.created.delete_this') }}</button>
                </form>
            </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('pastes.show', $paste->slug) }}" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-zinc-200">{{ __('safe_paste.created.view_paste') }}</a>
            <a href="{{ route('home') }}" class="rounded-md border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">{{ __('safe_paste.created.create_another') }}</a>
        </div>
    </section>
</x-layouts.app>
