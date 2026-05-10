<x-layouts.app title="{{ __('safe_paste.dashboard.title') }}">
    <section class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-semibold text-white">{{ __('safe_paste.dashboard.your_pastes') }}</h1>
                <p class="mt-1 text-sm text-zinc-400">{{ __('safe_paste.dashboard.subtitle') }}</p>
            </div>
            <a href="{{ route('home') }}" class="rounded-md bg-teal-500 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.dashboard.new_paste') }}</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-white/10 bg-zinc-900/80 p-5">
                <p class="text-sm text-zinc-400">{{ __('safe_paste.dashboard.total_pastes') }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $totalPastes }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-zinc-900/80 p-5">
                <p class="text-sm text-zinc-400">{{ __('safe_paste.dashboard.active_pastes') }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $activePastes }}</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-zinc-900/80 p-5">
                <p class="text-sm text-zinc-400">{{ __('safe_paste.dashboard.total_views') }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $totalViews }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-white/10 bg-zinc-900/80">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-zinc-950/60 text-left text-xs uppercase tracking-wide text-zinc-400">
                        <tr>
                            <th class="px-4 py-3">{{ __('safe_paste.common.title') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.dashboard.slug_link') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.common.visibility') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.common.views') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.common.expires') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.common.status') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.common.created') }}</th>
                            <th class="px-4 py-3">{{ __('safe_paste.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($pastes as $paste)
                            <tr class="text-zinc-200">
                                <td class="px-4 py-3">{{ $paste->title ?: __('safe_paste.common.untitled') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('pastes.show', $paste->slug) }}" class="font-mono text-teal-300 hover:text-teal-200">{{ $paste->slug }}</a>
                                </td>
                                <td class="px-4 py-3">{{ __("safe_paste.visibility.{$paste->visibility}") }}</td>
                                <td class="px-4 py-3">{{ $paste->views_count }}</td>
                                <td class="px-4 py-3">{{ $paste->expires_at?->toDateTimeString() ?? __('safe_paste.common.never') }}</td>
                                <td class="px-4 py-3">{{ $paste->statusLabel() }}</td>
                                <td class="px-4 py-3">{{ $paste->created_at->toDateString() }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('dashboard.pastes.show', $paste) }}" class="text-teal-300 hover:text-teal-200">{{ __('safe_paste.common.view') }}</a>
                                        <a href="{{ route('dashboard.pastes.edit', $paste) }}" class="text-sky-300 hover:text-sky-200">{{ __('safe_paste.common.edit') }}</a>
                                        <form method="POST" action="{{ route('dashboard.pastes.destroy', $paste) }}" onsubmit="return confirm('{{ __('safe_paste.dashboard.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-300 hover:text-red-200">{{ __('safe_paste.common.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-zinc-400">{{ __('safe_paste.dashboard.no_pastes') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $pastes->links() }}
    </section>
</x-layouts.app>
