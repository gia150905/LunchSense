<x-app-layout>
    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('home') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:scale-105 active:scale-95 transition">
                <span class="text-sm">⬅️</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Notifications</h1>
                <p class="text-xs text-text-muted">Real-time alerts and alerts regarding your dining orders</p>
            </div>
        </div>

        <!-- Notification cards list -->
        <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-gray-100">
            <div class="space-y-4">
                
                <!-- Notification 1 -->
                <div class="flex gap-4 items-start py-3 border-b border-gray-50 last:border-0 relative">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary mt-1.5 flex-shrink-0"></span>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="text-xs font-black text-text-main">Order Ready for Pickup! 🍔</h4>
                            <span class="text-[9px] text-text-muted font-bold">5m ago</span>
                        </div>
                        <p class="text-xs text-text-muted mt-1 leading-relaxed">
                            Your order is fresh and ready! Please go to <strong class="text-primary">Counter A</strong> and scan your express pickup pass.
                        </p>
                    </div>
                </div>

                <!-- Notification 2 -->
                <div class="flex gap-4 items-start py-3 border-b border-gray-50 last:border-0 relative">
                    <span class="w-2.5 h-2.5 rounded-full bg-accent mt-1.5 flex-shrink-0"></span>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="text-xs font-black text-text-main">Seat Reservation Confirmed 🪑</h4>
                            <span class="text-[9px] text-text-muted font-bold">12m ago</span>
                        </div>
                        <p class="text-xs text-text-muted mt-1 leading-relaxed">
                            Your seat at <strong class="text-accent">Table #12 (Zone B)</strong> is confirmed. Feel free to use the in-app directions panel to navigate.
                        </p>
                    </div>
                </div>

                <!-- Notification 3 -->
                <div class="flex gap-4 items-start py-3 border-b border-gray-50 last:border-0 relative">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary mt-1.5 flex-shrink-0"></span>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="text-xs font-black text-text-main">Top Up Successful! 💳</h4>
                            <span class="text-[9px] text-text-muted font-bold">1h ago</span>
                        </div>
                        <p class="text-xs text-text-muted mt-1 leading-relaxed">
                            You successfully topped up <strong class="text-text-main">RM 50.00</strong> to your digital wallet using DuitNow QR.
                        </p>
                    </div>
                </div>

                <!-- Notification 4 -->
                <div class="flex gap-4 items-start py-3 border-b border-gray-50 last:border-0 relative">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300 mt-1.5 flex-shrink-0"></span>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="text-xs font-black text-text-muted">Order Placed</h4>
                            <span class="text-[9px] text-text-muted font-bold">2h ago</span>
                        </div>
                        <p class="text-xs text-text-muted mt-1 leading-relaxed">
                            Order #1 has been registered in the system. Kitchen staff will begin preparation at 12:35 PM.
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
