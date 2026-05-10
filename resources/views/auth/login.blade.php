<x-layouts.app title="{{ __('safe_paste.auth.login_title') }}">
    <section class="mx-auto max-w-md">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-semibold tracking-tight text-white">{{ __('safe_paste.auth.login_title') }}</h1>
            <p class="mt-2 text-sm text-zinc-400">{{ __('safe_paste.auth.login_subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/20">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                    @error('email') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.password') }}</label>
                    <input id="password" name="password" type="password" required class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                    @error('password') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-3 text-sm text-zinc-200">
                    <input type="checkbox" name="remember" value="1" class="rounded border-white/10 bg-zinc-950 text-teal-500 focus:ring-teal-500">
                    {{ __('safe_paste.auth.remember') }}
                </label>

                <button class="w-full rounded-md bg-teal-500 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                    {{ __('safe_paste.auth.login_title') }}
                </button>
            </div>
        </form>

        <p class="mt-5 text-center text-sm text-zinc-400">
            {{ __('safe_paste.auth.no_account') }}
            <a href="{{ route('register') }}" class="font-medium text-teal-300 hover:text-teal-200">{{ __('safe_paste.auth.create_one') }}</a>
            /
            <a href="{{ route('home') }}" class="font-medium text-teal-300 hover:text-teal-200">{{ __('safe_paste.auth.continue_guest') }}</a>
        </p>
    </section>
</x-layouts.app>
