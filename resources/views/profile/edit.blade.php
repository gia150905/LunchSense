<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <!-- Student Avatar Greeting & Details -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-3xl font-black text-primary">
                😎
            </div>
            <div>
                <h2 class="text-xl font-black text-text-main leading-tight">{{ Auth::user()->name }}</h2>
                <p class="text-xs text-text-muted mt-1 uppercase font-bold tracking-wider">{{ Auth::user()->role }} Account</p>
                <p class="text-xs text-text-muted mt-0.5">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- LunchSense Wallet Status Card -->
        <div class="bg-gradient-to-br from-primary to-emerald-600 rounded-3xl p-6 text-white shadow-soft relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 text-8xl opacity-10 pointer-events-none">💳</div>
            <p class="text-white/80 text-xs font-bold uppercase tracking-wider">LunchSense Wallet</p>
            <h3 class="text-3xl font-black mt-1">RM {{ number_format(Auth::user()->wallet_balance, 2) }}</h3>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('wallet.topup') }}" class="bg-white text-primary font-black text-xs px-4 py-2.5 rounded-xl shadow-sm hover:bg-white/95 transition">Top Up Balance</a>
                <a href="{{ route('wallet.history') }}" class="bg-emerald-700/40 border border-white/20 text-white font-black text-xs px-4 py-2.5 rounded-xl hover:bg-emerald-700/60 transition">View History ➔</a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
