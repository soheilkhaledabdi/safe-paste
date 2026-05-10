<x-layouts.app title="{{ __('safe_paste.home.title') }}">
    <section class="mx-auto max-w-3xl">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">{{ __('safe_paste.home.title') }}</h1>
            <p class="mt-3 text-sm text-zinc-400">{{ __('safe_paste.home.subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('pastes.store') }}" class="rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/20">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.title') }}</label>
                    <input id="title" name="title" value="{{ old('title') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400" placeholder="{{ __('safe_paste.home.title_placeholder') }}">
                    @error('title') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.content') }}</label>
                    <textarea id="content" name="content" required rows="16" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 font-mono text-sm text-white outline-none focus:border-teal-400" placeholder="{{ __('safe_paste.home.content_placeholder') }}">{{ old('content') }}</textarea>
                    @error('content') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="language" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.language') }}</label>
                        <select id="language" name="language" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                            @foreach ($languages as $language)
                                <option value="{{ $language }}" @selected(old('language', 'text') === $language)>{{ ucfirst($language) }}</option>
                            @endforeach
                        </select>
                        @error('language') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.password') }}</label>
                        <input id="password" name="password" type="password" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400" placeholder="{{ __('safe_paste.common.optional') }}">
                        @error('password') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div>
                        <label for="expires_in" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.expires') }}</label>
                        <select id="expires_in" name="expires_in" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                            <option value="never" @selected(old('expires_in', 'never') === 'never')>{{ __('safe_paste.common.never') }}</option>
                            <option value="10_minutes" @selected(old('expires_in') === '10_minutes')>{{ __('safe_paste.expiry.10_minutes') }}</option>
                            <option value="1_hour" @selected(old('expires_in') === '1_hour')>{{ __('safe_paste.expiry.1_hour') }}</option>
                            <option value="1_day" @selected(old('expires_in') === '1_day')>{{ __('safe_paste.expiry.1_day') }}</option>
                            <option value="7_days" @selected(old('expires_in') === '7_days')>{{ __('safe_paste.expiry.7_days') }}</option>
                            <option value="30_days" @selected(old('expires_in') === '30_days')>{{ __('safe_paste.expiry.30_days') }}</option>
                        </select>
                        @error('expires_in') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="max_views" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.home.max_views') }}</label>
                        <input id="max_views" name="max_views" type="number" min="1" max="10000" value="{{ old('max_views') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400" placeholder="{{ __('safe_paste.common.unlimited') }}">
                        @error('max_views') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                    </div>

                    @auth
                        <div>
                            <label for="visibility" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.visibility') }}</label>
                            <select id="visibility" name="visibility" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                                <option value="private" @selected(old('visibility') === 'private')>{{ __('safe_paste.visibility.private') }}</option>
                                <option value="unlisted" @selected(old('visibility', 'unlisted') === 'unlisted')>{{ __('safe_paste.visibility.unlisted') }}</option>
                                <option value="public" @selected(old('visibility') === 'public')>{{ __('safe_paste.visibility.public') }}</option>
                            </select>
                            @error('visibility') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                        </div>
                    @endauth
                </div>

                <label class="flex items-center gap-3 text-sm text-zinc-200">
                    <input type="checkbox" name="burn_after_reading" value="1" @checked(old('burn_after_reading')) class="rounded border-white/10 bg-zinc-950 text-teal-500 focus:ring-teal-500">
                    {{ __('safe_paste.home.burn_after_reading') }}
                </label>

                <button type="submit" class="w-full rounded-md bg-teal-500 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                    {{ __('safe_paste.home.submit') }}
                </button>
            </div>
        </form>
    </section>
</x-layouts.app>
