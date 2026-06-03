<x-app-layout>
    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('seats.index') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:scale-105 active:scale-95 transition">
                <span class="text-sm">⬅️</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Live Navigation</h1>
                <p class="text-xs text-text-muted">Directions to Table #{{ $activeReservation['table_number'] }}</p>
            </div>
        </div>

        <!-- Live distance banner -->
        <div class="bg-primary rounded-3xl p-6 text-white text-center shadow-soft mb-6 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-lg"></div>
            <div class="text-3xl mb-1">📍</div>
            <h2 class="text-2xl font-black">12 meters away</h2>
            <p class="text-xs text-white/90 mt-1 font-medium">Estimated walk time: 30 seconds</p>
        </div>

        <!-- Styled Visual Navigation Path Map (Vertical Stepper) -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-6 text-center">Path Guidance</h3>
            
            <div class="relative pl-8 space-y-8">
                <!-- Vertical line -->
                <div class="absolute left-3.5 top-2 bottom-2 w-0.5 bg-dashed border-l-2 border-primary/30"></div>

                <!-- Step 1: Start -->
                <div class="relative">
                    <span class="absolute -left-8 w-7.5 h-7.5 rounded-full bg-primary/20 border-2 border-primary flex items-center justify-center text-xs font-bold text-primary">1</span>
                    <div>
                        <h4 class="text-sm font-black text-text-main">Enter Cafeteria</h4>
                        <p class="text-xs text-text-muted mt-0.5">Start at the DKG 6 main entrance doors.</p>
                    </div>
                </div>

                <!-- Step 2: Cashier -->
                <div class="relative">
                    <span class="absolute -left-8 w-7.5 h-7.5 rounded-full bg-primary/20 border-2 border-primary flex items-center justify-center text-xs font-bold text-primary">2</span>
                    <div>
                        <h4 class="text-sm font-black text-text-main">Walk Past counter</h4>
                        <p class="text-xs text-text-muted mt-0.5">Walk straight past Counter A and Counter B (10 meters).</p>
                    </div>
                </div>

                <!-- Step 3: Turn -->
                <div class="relative">
                    <span class="absolute -left-8 w-7.5 h-7.5 rounded-full bg-primary/20 border-2 border-primary flex items-center justify-center text-xs font-bold text-primary">3</span>
                    <div>
                        <h4 class="text-sm font-black text-text-main">Turn Right</h4>
                        <p class="text-xs text-text-muted mt-0.5">Turn right towards the Window Side seating zone (Zone B).</p>
                    </div>
                </div>

                <!-- Step 4: Table -->
                <div class="relative">
                    <span class="absolute -left-8 w-7.5 h-7.5 rounded-full bg-accent/20 border-2 border-accent flex items-center justify-center text-xs font-bold text-accent">4</span>
                    <div>
                        <h4 class="text-sm font-black text-text-main">Find Table #{{ $activeReservation['table_number'] }}</h4>
                        <p class="text-xs text-text-muted mt-0.5">Your spot ({{ $activeReservation['seat_label'] }}) is located next to the window bay.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arrived CTA -->
        <a href="{{ route('seats.index') }}" class="w-full bg-primary text-white font-black text-xs py-3.5 rounded-2xl shadow-glow-primary active:scale-95 transition text-center block">
            Check-In at Table #{{ $activeReservation['table_number'] }} ➔
        </a>

    </div>
</x-app-layout>
