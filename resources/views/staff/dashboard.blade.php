<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8 pb-24">
        
        <!-- Header banner -->
        <div class="bg-gradient-to-r from-emerald-900 to-green-950 rounded-3xl p-8 text-white mb-8 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <span class="bg-primary/20 text-primary-light text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider">Operational Control Center</span>
                <h1 class="text-3xl font-black mt-2 font-sans tracking-tight">LunchSense Staff Panel</h1>
                <p class="text-xs text-white/70 mt-1">Manage live customer orders, food stocks, and seat layout coordinates.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Layout for 3 main sections -->
        <div class="space-y-8">
            
            <!-- SECTION 1: Active Orders Queue -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-black text-text-main">Real-time Orders Queue</h2>
                        <p class="text-xs text-text-muted">1-click order state updates for cafeteria pickup synchronization</p>
                    </div>
                    <span class="bg-gray-100 text-text-muted text-[10px] font-black px-3 py-1 rounded-full">Today: {{ count($orders) }} orders</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-text-muted text-[11px] font-bold uppercase tracking-wider">
                                <th class="pb-3 pr-4">Order ID</th>
                                <th class="pb-3 px-4">Student</th>
                                <th class="pb-3 px-4">Est. Pickup</th>
                                <th class="pb-3 px-4">Ordered Items</th>
                                <th class="pb-3 px-4 text-center">Status</th>
                                <th class="pb-3 pl-4 text-right">Quick Transition</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4 pr-4 font-mono font-black text-text-main">#{{ $order->id }}</td>
                                    <td class="py-4 px-4 font-bold text-text-main">{{ $order->user->name }}</td>
                                    <td class="py-4 px-4 font-black text-primary">{{ \Carbon\Carbon::parse($order->pickup_time)->format('h:i A') }}</td>
                                    <td class="py-4 px-4 text-text-muted font-medium">
                                        {{ $order->items->map(fn($i) => $i->quantity . 'x ' . $i->menu->name)->join(', ') }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                                            {{ $order->order_status == 'waiting' ? 'bg-gray-100 text-text-muted' : '' }}
                                            {{ $order->order_status == 'preparing' ? 'bg-orange-50 text-accent' : '' }}
                                            {{ $order->order_status == 'ready' ? 'bg-emerald-50 text-primary' : '' }}
                                            {{ $order->order_status == 'completed' ? 'bg-gray-200 text-gray-500' : '' }}
                                        ">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="py-4 pl-4 text-right">
                                        <!-- Quick Transition Action Pills -->
                                        <form action="{{ route('staff.order.status', $order) }}" method="POST" class="inline-flex gap-1">
                                            @csrf
                                            @foreach(['waiting', 'preparing', 'ready', 'completed'] as $statusOption)
                                                <button type="submit" name="status" value="{{ $statusOption }}"
                                                        :class="[
                                                            '{{ $order->order_status }}' === '{{ $statusOption }}' 
                                                            ? 'bg-primary text-white border-transparent' 
                                                            : 'bg-white text-text-muted hover:bg-gray-50 border-gray-200'
                                                        ]"
                                                        class="px-2.5 py-1.5 border text-[10px] font-bold rounded-lg transition active:scale-95 capitalize">
                                                    {{ $statusOption }}
                                                </button>
                                            @endforeach
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-text-muted font-bold">No orders placed today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: Menu Stock & Availability -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-lg font-black text-text-main">Menu Stock Manager</h2>
                    <p class="text-xs text-text-muted">Adjust food portions and availability statuses in real-time</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($menus as $menu)
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 flex flex-col justify-between hover:shadow-soft transition">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-sm text-text-main">{{ $menu->name }}</h4>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider
                                        {{ $menu->status === 'in_stock' ? 'bg-emerald-100 text-primary' : '' }}
                                        {{ $menu->status === 'low_stock' ? 'bg-orange-100 text-accent' : '' }}
                                        {{ $menu->status === 'sold_out' ? 'bg-red-100 text-danger' : '' }}
                                    ">
                                        {{ str_replace('_', ' ', $menu->status) }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-text-muted uppercase font-bold">{{ $menu->category }}</p>
                            </div>
                            
                            <!-- Set Stock Form -->
                            <form action="{{ route('staff.menu.stock', $menu) }}" method="POST" class="mt-4 pt-3 border-t border-gray-200/50 flex gap-2 items-center">
                                @csrf
                                <div class="flex-1">
                                    <label class="text-[9px] font-bold text-text-muted uppercase block mb-1">Set Stock Count</label>
                                    <input type="number" name="stock" value="{{ $menu->stock }}" min="0" 
                                           class="w-full bg-white border border-gray-200 rounded-xl py-1.5 px-3 text-xs font-bold text-text-main focus:ring-primary focus:ring-1">
                                </div>
                                <button type="submit" class="bg-primary text-white font-black text-[10px] px-3.5 py-2.5 rounded-xl hover:bg-opacity-95 transition shadow-sm self-end">
                                    Save
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SECTION 3: Seats & Layout Controls -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-lg font-black text-text-main">Seat & Zone Coordinator</h2>
                    <p class="text-xs text-text-muted">Manage table vacancies, coordinates, cleaning phases, and Social dining setups</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($seats as $seat)
                        <div class="rounded-2xl p-4 border flex flex-col justify-between hover:shadow-soft transition
                            {{ $seat->status === 'full' ? 'bg-red-50/50 border-red-200 text-red-900' : '' }}
                            {{ $seat->status === 'cleaning' ? 'bg-orange-50/50 border-orange-200 text-orange-900' : '' }}
                            {{ $seat->status === 'available' ? 'bg-emerald-50/50 border-emerald-200 text-emerald-950' : '' }}
                        ">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-black text-sm">Table #{{ $seat->table_number }}</h4>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider
                                        {{ $seat->status === 'available' ? 'bg-primary/20 text-primary' : '' }}
                                        {{ $seat->status === 'full' ? 'bg-danger/25 text-danger' : '' }}
                                        {{ $seat->status === 'cleaning' ? 'bg-accent/30 text-yellow-800' : '' }}
                                    ">
                                        {{ $seat->status }}
                                    </span>
                                </div>
                                <p class="text-[9px] text-text-muted font-bold uppercase tracking-wider mb-3">{{ $seat->zone }} (Window: {{ $seat->zone === 'Zone B' ? 'Yes' : 'No' }})</p>
                            </div>

                            <!-- Update Seat Form -->
                            <form action="{{ route('staff.seat.status', $seat) }}" method="POST" class="pt-3 border-t border-gray-200/50 space-y-3">
                                @csrf
                                <!-- Available count -->
                                <div class="flex justify-between items-center">
                                    <label class="text-[10px] font-bold text-text-muted uppercase">Vacancy:</label>
                                    <div class="flex items-center gap-1">
                                        <input type="number" name="available_seats" value="{{ $seat->available_seats }}" min="0" max="{{ $seat->capacity }}" 
                                               class="w-12 text-center bg-white border border-gray-200 rounded-lg p-1 text-xs font-bold text-text-main">
                                        <span class="text-[10px] text-text-muted">/ {{ $seat->capacity }}</span>
                                    </div>
                                </div>

                                <!-- Status selection -->
                                <div class="flex justify-between items-center">
                                    <label class="text-[10px] font-bold text-text-muted uppercase">Status:</label>
                                    <select name="status" class="bg-white border border-gray-200 rounded-lg p-1 text-xs font-bold text-text-main">
                                        <option value="available" {{ $seat->status === 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="cleaning" {{ $seat->status === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                                        <option value="full" {{ $seat->status === 'full' ? 'selected' : '' }}>Full</option>
                                    </select>
                                </div>

                                <!-- Social Mode Toggle -->
                                <div class="flex justify-between items-center">
                                    <label class="text-[10px] font-bold text-text-muted uppercase">Social Mode:</label>
                                    <input type="checkbox" name="social_mode" value="1" {{ $seat->social_mode ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary h-3.5 w-3.5">
                                </div>

                                <button type="submit" class="w-full bg-primary text-white font-black text-[10px] py-2 rounded-xl hover:bg-opacity-95 transition shadow-sm text-center">
                                    Update Table
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
