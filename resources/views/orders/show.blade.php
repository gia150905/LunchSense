<x-app-layout>
    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <!-- Header area -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('orders.index') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:scale-105 active:scale-95 transition">
                <span class="text-sm">⬅️</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Pickup Pass</h1>
                <p class="text-xs text-text-muted">Order ID: #{{ $order->id }}</p>
            </div>
        </div>

        @if(session('success'))
            <!-- Animated Green Success Checkmark Banner -->
            <div class="bg-primary rounded-3xl p-6 text-white text-center shadow-soft mb-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-lg"></div>
                <div class="text-4xl mb-2">🎉</div>
                <h2 class="text-lg font-black">Order Placed Successfully!</h2>
                <p class="text-xs text-white/90 mt-1 font-medium">Your lunch spot is locked in. Let's get cooking!</p>
            </div>
        @endif

        <!-- Seating note if active seat exists -->
        @if(session('active_reservation'))
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6 flex items-center gap-3">
                <span class="text-xl">🪑</span>
                <div>
                    <h4 class="text-xs font-black text-primary uppercase tracking-wider">Spot Locked In</h4>
                    <p class="text-[10px] text-text-muted font-bold mt-0.5">Table #{{ session('active_reservation.table_number') }} ({{ session('active_reservation.zone') }}) is reserved for you!</p>
                </div>
            </div>
        @endif

        <!-- Express QR Code Pickup Pass Container -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center mb-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary to-emerald-400"></div>
            
            <span class="bg-primary/10 text-primary text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">Express Pickup Pass</span>
            <h3 class="font-black text-2xl text-text-main leading-tight mb-1" x-text="'Counter ' + '{{ explode(' ', $order->pickup_counter)[1] ?? 'A' }}'"></h3>
            <p class="text-xs text-text-muted mb-6">Present this QR Code to the kitchen staff to collect your order.</p>

            <!-- QR code wrapper -->
            <div class="bg-white p-4 rounded-2xl shadow-inner border border-gray-100 flex justify-center mb-4 transition duration-300 hover:scale-105">
                {!! $qr !!}
            </div>

            <p class="font-mono text-xs font-black tracking-widest bg-gray-100 px-4 py-2 rounded-xl text-gray-500 mb-2">{{ $order->qr_code }}</p>
            <span class="text-[9px] text-text-muted font-bold">Expires: {{ \Carbon\Carbon::parse($order->pickup_time)->addHours(2)->format('h:i A') }}</span>
        </div>

        <!-- Order Stepper / Status Timeline -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6">
            <h4 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">Preparation Timeline</h4>
            <x-order-stepper :status="$order->order_status" />
        </div>

        <!-- Ordered Items Summary -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6">
            <h4 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">Items Ordered</h4>
            
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-primary/10 text-primary font-black text-xs flex items-center justify-center">
                                {{ $item->quantity }}
                            </span>
                            <span class="text-xs font-black text-text-main">{{ $item->menu->name }}</span>
                        </div>
                        <span class="text-xs font-black text-text-main">RM {{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="border-t border-gray-100 mt-4 pt-4 space-y-2 text-xs font-bold text-text-muted">
                <div class="flex justify-between">
                    <span>Service Fee</span>
                    <span class="text-text-main">RM {{ number_format($order->service_fee, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Payment Method</span>
                    <span class="text-primary font-black">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between text-sm font-black pt-3 border-t border-gray-50 text-text-main">
                    <span>Total Payment</span>
                    <span class="text-primary">RM {{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
