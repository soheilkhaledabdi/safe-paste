<x-layouts.app title="{{ __('safe_paste.nav.register') }}">
    <section class="mx-auto max-w-md">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-semibold tracking-tight text-white">{{ __('safe_paste.auth.register_title') }}</h1>
            <p class="mt-2 text-sm text-zinc-400">{{ __('safe_paste.auth.register_subtitle') }}</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/20">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.name') }}</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                    @error('name') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                    @error('email') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.common.password') }}</label>
                    <input id="password" name="password" type="password" required class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                    @error('password') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-200">{{ __('safe_paste.auth.confirm_password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white outline-none focus:border-teal-400">
                </div>

                <button class="w-full rounded-md bg-teal-500 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                    {{ __('safe_paste.auth.register_title') }}
                </button>
            </div>
        </form>

        <p class="mt-5 text-center text-sm text-zinc-400">
            {{ __('safe_paste.auth.already_account') }}
            <a href="{{ route('login') }}" class="font-medium text-teal-300 hover:text-teal-200">{{ __('safe_paste.auth.login_title') }}</a>
            /
            <a href="{{ route('home') }}" class="font-medium text-teal-300 hover:text-teal-200">{{ __('safe_paste.auth.continue_guest') }}</a>
        </p>
    </section>
</x-layouts.app>
