<x-layouts.app title="{{ __('safe_paste.dashboard.edit_paste') }}">
    <section class="mx-auto max-w-3xl">
        <h1 class="mb-6 text-2xl font-semibold text-white">{{ __('safe_paste.dashboard.edit_paste') }}</h1>

        <form method="POST" action="{{ route('dashboard.pastes.update', $paste) }}" class="rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/20">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.title') }}</label>
                    <input id="title" name="title" value="{{ old('title', $paste->title) }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                    @error('title') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.content') }}</label>
                    <textarea id="content" name="content" required rows="16" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 font-mono text-sm text-white outline-none focus:border-teal-400">{{ old('content', $content) }}</textarea>
                    @error('content') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="language" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.language') }}</label>
                        <select id="language" name="language" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                            @foreach ($languages as $language)
                                <option value="{{ $language }}" @selected(old('language', $paste->language) === $language)>{{ ucfirst($language) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="visibility" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.visibility') }}</label>
                        <select id="visibility" name="visibility" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                            <option value="private" @selected(old('visibility', $paste->visibility) === 'private')>{{ __('safe_paste.visibility.private') }}</option>
                            <option value="unlisted" @selected(old('visibility', $paste->visibility) === 'unlisted')>{{ __('safe_paste.visibility.unlisted') }}</option>
                            <option value="public" @selected(old('visibility', $paste->visibility) === 'public')>{{ __('safe_paste.visibility.public') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div>
                        <label for="expires_in" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.expiry.change') }}</label>
                        <select id="expires_in" name="expires_in" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                            <option value="">{{ __('safe_paste.expiry.keep_current') }}</option>
                            <option value="never">{{ __('safe_paste.common.never') }}</option>
                            <option value="10_minutes">{{ __('safe_paste.expiry.10_minutes') }}</option>
                            <option value="1_hour">{{ __('safe_paste.expiry.1_hour') }}</option>
                            <option value="1_day">{{ __('safe_paste.expiry.1_day') }}</option>
                            <option value="7_days">{{ __('safe_paste.expiry.7_days') }}</option>
                            <option value="30_days">{{ __('safe_paste.expiry.30_days') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="max_views" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.home.max_views') }}</label>
                        <input id="max_views" name="max_views" type="number" min="1" max="10000" value="{{ old('max_views', $paste->max_views) }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400" placeholder="{{ __('safe_paste.common.unlimited') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.dashboard.new_password') }}</label>
                        <input id="password" name="password" type="password" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400" placeholder="{{ __('safe_paste.expiry.keep_current') }}">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 text-sm text-zinc-200">
                        <input type="checkbox" name="burn_after_reading" value="1" @checked(old('burn_after_reading', $paste->burn_after_reading)) class="rounded border-white/10 bg-zinc-950 text-teal-500 focus:ring-teal-500">
                        {{ __('safe_paste.home.burn_after_reading') }}
                    </label>
                    <label class="flex items-center gap-3 text-sm text-zinc-200">
                        <input type="checkbox" name="remove_password" value="1" @checked(old('remove_password')) class="rounded border-white/10 bg-zinc-950 text-teal-500 focus:ring-teal-500">
                        {{ __('safe_paste.dashboard.remove_password') }}
                    </label>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button class="rounded-md bg-teal-500 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-400">{{ __('safe_paste.dashboard.save_changes') }}</button>
                    <a href="{{ route('dashboard.pastes.show', $paste) }}" class="rounded-md border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">{{ __('safe_paste.common.cancel') }}</a>
                </div>
            </div>
        </form>
    </section>
</x-layouts.app>
