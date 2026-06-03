<x-app-layout>
    <div x-data="{ activeTab: 'active' }" class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <!-- Header title -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">My Orders</h1>
                <p class="text-xs text-text-muted">Track your live orders and order histories</p>
            </div>
            <a href="{{ route('menus.index') }}" class="text-xs font-black text-primary bg-primary/10 px-3.5 py-2 rounded-full">➔ Order Food</a>
        </div>

        <!-- Custom tabs toggle -->
        <div class="bg-gray-100 p-1.5 rounded-2xl flex gap-1 mb-6 border border-gray-200/50">
            <button type="button" @click="activeTab = 'active'"
                    :class="activeTab === 'active' ? 'bg-white text-primary shadow-sm' : 'text-text-muted hover:text-text-main'"
                    class="flex-1 py-3 text-xs font-black rounded-xl text-center active:scale-[0.98] transition">
                Active Orders
            </button>
            <button type="button" @click="activeTab = 'history'"
                    :class="activeTab === 'history' ? 'bg-white text-primary shadow-sm' : 'text-text-muted hover:text-text-main'"
                    class="flex-1 py-3 text-xs font-black rounded-xl text-center active:scale-[0.98] transition">
                Past History
            </button>
        </div>

        <!-- 1. Active Tab Content -->
        <div x-show="activeTab === 'active'" class="space-y-4">
            @php
                $activeOrders = $orders->filter(fn($o) => in_array($o->order_status, ['waiting', 'preparing', 'ready']));
            @endphp
            
            @forelse($activeOrders as $order)
                <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary to-emerald-400"></div>
                    
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <span class="text-[10px] text-text-muted font-bold block uppercase tracking-wider">Pickup Pass</span>
                            <h3 class="font-black text-sm text-text-main">Order #{{ $order->id }}</h3>
                        </div>
                        <span class="text-xs font-black text-primary px-3 py-1 bg-primary/10 rounded-full uppercase tracking-wider">
                            {{ $order->order_status }}
                        </span>
                    </div>

                    <p class="text-xs text-text-muted mb-4 leading-relaxed">
                        Items: 
                        <span class="font-bold text-text-main">
                            {{ $order->items->map(fn($item) => $item->quantity . 'x ' . $item->menu->name)->join(', ') }}
                        </span>
                    </p>

                    <!-- Preparation Timeline status stepper inline -->
                    <div class="mb-5 pt-3 border-t border-gray-55">
                        <x-order-stepper :status="$order->order_status" />
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <div>
                            <span class="text-[9px] text-text-muted block font-bold">Estimated Pickup</span>
                            <span class="font-black text-sm text-text-main">{{ \Carbon\Carbon::parse($order->pickup_time)->format('h:i A') }}</span>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="bg-primary text-white font-black text-xs px-5 py-2.5 rounded-xl shadow-glow-primary active:scale-95 transition">
                            Open QR Pass ➔
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-8 border border-gray-100 text-center">
                    <span class="text-4xl block mb-2">🍱</span>
                    <h3 class="font-black text-sm text-text-main">No active orders</h3>
                    <p class="text-xs text-text-muted mt-1">Pre-order delicious meals on the Menu page.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. History Tab Content -->
        <div x-show="activeTab === 'history'" class="space-y-4">
            @php
                $pastOrders = $orders->filter(fn($o) => $o->order_status === 'completed');
            @endphp
            
            @forelse($pastOrders as $order)
                <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm relative">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="text-[10px] text-text-muted font-bold block uppercase tracking-wider">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                            <h3 class="font-black text-sm text-text-main">Order #{{ $order->id }}</h3>
                        </div>
                        <span class="text-xs font-black text-primary">RM {{ number_format($order->total_price, 2) }}</span>
                    </div>

                    <p class="text-xs text-text-muted mb-4">
                        {{ $order->items->map(fn($item) => $item->quantity . 'x ' . $item->menu->name)->join(', ') }}
                    </p>

                    <div class="flex gap-2 pt-2 border-t border-gray-50">
                        <a href="{{ route('orders.show', $order) }}" class="flex-1 bg-gray-50 border border-gray-200 text-text-muted hover:text-text-main hover:bg-gray-100 font-black text-xs py-2 rounded-xl text-center transition">
                            Receipt
                        </a>
                        
                        <!-- Quick Reorder Button using localStorage initialization -->
                        <button type="button" @click="
                            const cart = {};
                            @foreach($order->items as $item)
                                cart['{{ $item->menu_id }}'] = {
                                    id: '{{ $item->menu_id }}',
                                    name: '{{ $item->menu->name }}',
                                    price: {{ $item->menu->price }},
                                    qty: {{ $item->quantity }}
                                };
                            @endforeach
                            localStorage.setItem('lunchsense_cart', JSON.stringify(cart));
                            window.location.href = '{{ route('orders.create') }}';
                        " class="flex-1 bg-primary/10 hover:bg-primary text-primary hover:text-white font-black text-xs py-2 rounded-xl text-center transition">
                            Quick Re-order
                        </button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-8 border border-gray-100 text-center">
                    <span class="text-4xl block mb-2">⌛</span>
                    <h3 class="font-black text-sm text-text-main">No order history</h3>
                    <p class="text-xs text-text-muted mt-1">Meals you ordered previously will be logged here.</p>
                </div>
            @endforelse

            <!-- AI Re-order Recommendation Banner (Figma Screen 19) -->
            <div class="bg-gradient-to-r from-primary/10 to-emerald-500/10 rounded-3xl p-5 border border-primary/20 flex gap-3 relative overflow-hidden mt-6">
                <span class="text-2xl">🤖</span>
                <div>
                    <h4 class="text-xs font-black text-primary uppercase tracking-wider">AI Re-order Recommendation</h4>
                    <p class="text-[11px] text-text-main font-medium leading-relaxed mt-1">
                        Based on your dining behavior, you usually order <strong class="font-bold">Nasi Ayam</strong> on Wednesdays at <strong class="font-bold">12:30 PM</strong>. Pre-order by <span class="text-danger font-bold">12:15 PM</span> tomorrow to ensure availability!
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
