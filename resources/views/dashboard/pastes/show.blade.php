<x-layouts.app title="{{ $paste->title ?: __('safe_paste.common.untitled') }}">
    <section class="space-y-5">
        <div class="flex flex-col justify-between gap-4 rounded-lg border border-white/10 bg-zinc-900/80 p-5 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-semibold text-white">{{ $paste->title ?: __('safe_paste.common.untitled') }}</h1>
                <div class="mt-2 flex flex-wrap gap-2 text-xs text-zinc-400">
                    <span class="rounded bg-zinc-800 px-2 py-1">{{ __("safe_paste.visibility.{$paste->visibility}") }}</span>
                    <span class="rounded bg-zinc-800 px-2 py-1">{{ $paste->language }}</span>
                    <span class="rounded bg-zinc-800 px-2 py-1">{{ $paste->statusLabel() }}</span>
                    <span class="rounded bg-zinc-800 px-2 py-1">{{ __('safe_paste.common.expires') }}: {{ $paste->expires_at?->toDayDateTimeString() ?? __('safe_paste.common.never') }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('pastes.show', $paste->slug) }}" class="rounded-md border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">{{ __('safe_paste.paste.public_view') }}</a>
                <a href="{{ route('dashboard.pastes.edit', $paste) }}" class="rounded-md bg-teal-500 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.common.edit') }}</a>
            </div>
        </div>

        <pre class="overflow-x-auto rounded-lg border border-white/10 bg-zinc-950 p-5 font-mono text-sm leading-6 text-zinc-100">{{ $content }}</pre>
    </section>
</x-layouts.app>
