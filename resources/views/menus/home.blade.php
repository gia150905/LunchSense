<x-app-layout>
    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-7xl sm:px-6 lg:px-8">
        
        <!-- Header area for logo and quick actions -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-black text-primary tracking-tight">LunchSense</h1>
                <p class="text-xs text-text-muted">Smart Cafeteria Assistant • DKG 6</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('notifications.index') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center relative hover:scale-105 transition active:scale-95">
                    <span class="text-lg">🔔</span>
                    <span class="absolute -top-1 -right-1 bg-danger text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center border border-white">3</span>
                </a>
                <a href="{{ route('menus.index') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center relative hover:scale-105 transition active:scale-95">
                    <span class="text-lg">🛒</span>
                </a>
            </div>
        </div>

        <!-- Greeting card -->
        <div class="bg-gradient-to-br from-primary to-emerald-600 rounded-3xl p-6 text-white mb-6 shadow-soft relative overflow-hidden">
            <!-- Decorative blur shapes -->
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
            
            <div class="relative z-10">
                <p class="text-white/80 text-xs font-bold uppercase tracking-wider">Welcome back</p>
                @php
                    $displayName = preg_replace('/^(Student|Staff|Admin)\s+/i', '', Auth::user()->name);
                    $firstWord = explode(' ', $displayName)[0];
                @endphp
                <h2 class="text-2xl font-black mt-1">Good Afternoon, {{ $firstWord }}! 👋</h2>
                <p class="text-white/90 text-sm mt-2 font-medium">Ready for lunch? Your current digital wallet balance is <strong class="font-extrabold">RM {{ number_format(Auth::user()->wallet_balance, 2) }}</strong>.</p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('wallet.topup') }}" class="bg-white text-primary font-black text-xs px-4 py-2 rounded-xl hover:bg-white/90 transition shadow-sm">Top Up Wallet</a>
                    <a href="{{ route('menus.index') }}" class="bg-emerald-700/50 border border-white/20 text-white font-black text-xs px-4 py-2 rounded-xl hover:bg-emerald-700/70 transition">Order Food</a>
                </div>
            </div>
        </div>

        <!-- Live Status Cafeteria panel -->
        <div class="mb-6">
            <h3 class="text-sm font-black text-text-main uppercase tracking-wider mb-3">DKG 6 Live Status</h3>
            
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                <!-- Food Availability -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-text-muted font-bold">Food Available</span>
                        <div class="text-xl font-black text-primary mt-1">{{ $foodAvailabilityPercent }}%</div>
                    </div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                        <div class="bg-primary h-full rounded-full" style="width: {{ $foodAvailabilityPercent }}%"></div>
                    </div>
                </div>

                <!-- Seats available -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-text-muted font-bold">Seats Free</span>
                        <div class="text-xl font-black text-primary mt-1">{{ $availableSeats }} Seats</div>
                    </div>
                    <div class="text-[10px] text-emerald-600 font-bold mt-3">⚡ Live updates</div>
                </div>

                <!-- Crowd Level -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-text-muted font-bold">Crowd Level</span>
                        <div class="text-xl font-black text-{{ $crowdStatus['color'] }} mt-1">{{ $crowdStatus['label'] }}</div>
                    </div>
                    <div class="text-[10px] text-text-muted mt-3">Current density</div>
                </div>

                <!-- Best Arrival -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-text-muted font-bold">Best Arrival</span>
                        <div class="text-xl font-black text-accent mt-1">{{ $crowdStatus['best_arrival'] }}</div>
                    </div>
                    <div class="text-[10px] text-text-muted mt-3">Least queue</div>
                </div>

                <!-- Wait time -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 col-span-2 sm:col-span-1 flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-text-muted font-bold">Wait Time</span>
                        <div class="text-xl font-black text-text-main mt-1">{{ $crowdStatus['wait_time'] }}</div>
                    </div>
                    <div class="text-[10px] text-text-muted mt-3">Avg prep time</div>
                </div>
            </div>
        </div>

        <!-- AI Insights Panel -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6 relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 text-7xl opacity-5 pointer-events-none">🤖</div>
            <div class="flex items-center gap-2 mb-3">
                <span class="text-lg">🤖</span>
                <h3 class="text-sm font-black text-text-main uppercase tracking-wider">LunchSense AI Insight</h3>
            </div>
            
            <div class="space-y-3 mb-4">
                <div class="flex gap-3 items-start">
                    <span class="text-emerald-500 mt-0.5">●</span>
                    <p class="text-xs text-text-main font-medium leading-relaxed">
                        <strong class="font-bold text-primary">Nasi Ayam</strong> is selling fast today. Estimated sold out in <span class="text-danger font-bold">20 mins</span>. Pre-order now to lock in your meal!
                    </p>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="text-emerald-500 mt-0.5">●</span>
                    <p class="text-xs text-text-main font-medium leading-relaxed">
                        Crowd traffic is dropping rapidly. Peak crowd will disperse by <strong class="font-bold">1:40 PM</strong>.
                    </p>
                </div>
                <div class="flex gap-3 items-start">
                    <span class="text-emerald-500 mt-0.5">●</span>
                    <p class="text-xs text-text-main font-medium leading-relaxed">
                        Tables in <strong class="font-bold">Zone B</strong> are set to Social Mode. Perfect time to meet new dining mates!
                    </p>
                </div>
            </div>

            <a href="{{ route('seats.index') }}" class="w-full bg-primary/10 hover:bg-primary/20 text-primary font-black text-xs py-3 rounded-2xl block text-center transition">
                Reserve Seat & View Layout Map ➔
            </a>
        </div>

        <!-- Crowd Occupancy Forecast Chart -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-sm font-black text-text-main uppercase tracking-wider mb-4">Crowd Forecast</h3>
            
            <div class="flex justify-between items-end h-32 gap-3 px-2">
                @foreach($forecast as $time => $occupancy)
                    <div class="flex flex-col items-center flex-1">
                        <div class="text-[9px] font-black text-text-muted mb-1">{{ $occupancy }}%</div>
                        <div class="w-full bg-gray-100 rounded-full h-20 flex items-end overflow-hidden">
                            <div class="w-full bg-gradient-to-t from-primary to-emerald-400 rounded-full" style="height: {{ $occupancy }}%"></div>
                        </div>
                        <span class="text-[9px] font-bold text-text-muted mt-2 text-center whitespace-nowrap">{{ explode(' ', $time)[0] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Eco Impact -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-lg">🌱</span>
                <h3 class="text-sm font-black text-text-main uppercase tracking-wider">Eco Impact</h3>
            </div>
            
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-emerald-50/50 rounded-2xl p-3 text-center">
                    <div class="text-base font-black text-primary">{{ $ecoImpact['co2'] }}</div>
                    <div class="text-[9px] text-text-muted font-bold mt-1">CO2 Reduced</div>
                </div>
                <div class="bg-emerald-50/50 rounded-2xl p-3 text-center">
                    <div class="text-base font-black text-primary">{{ $ecoImpact['water'] }}</div>
                    <div class="text-[9px] text-text-muted font-bold mt-1">Water Saved</div>
                </div>
                <div class="bg-emerald-50/50 rounded-2xl p-3 text-center">
                    <div class="text-base font-black text-primary">{{ $ecoImpact['packaging'] }}</div>
                    <div class="text-[9px] text-text-muted font-bold mt-1">Eco Packaging</div>
                </div>
            </div>
            <p class="text-[10px] text-text-muted text-center mt-3 font-semibold">Your orders contributed to planting 2 trees this month!</p>
        </div>

    </div>
</x-app-layout>
