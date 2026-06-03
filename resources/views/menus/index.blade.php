<x-app-layout>
    <div x-data="{
        selectedCategory: '{{ $category ?? 'Popular' }}',
        searchQuery: '{{ $search ?? '' }}',
        cart: {},
        activeFood: null,
        detailOpen: false,
        selectedTime: '12:30 PM',
        quantity: 1,
        
        get cartTotal() {
            let total = 0;
            for (let id in this.cart) {
                total += this.cart[id].price * this.cart[id].qty;
            }
            return total;
        },
        
        get cartCount() {
            let count = 0;
            for (let id in this.cart) {
                count += this.cart[id].qty;
            }
            return count;
        },
        
        addToCart(menu) {
            if (this.cart[menu.id]) {
                this.cart[menu.id].qty += this.quantity;
            } else {
                this.cart[menu.id] = {
                    id: menu.id,
                    name: menu.name,
                    price: parseFloat(menu.price),
                    qty: this.quantity
                };
            }
            this.detailOpen = false;
            this.quantity = 1;
            // Save cart to localStorage
            localStorage.setItem('lunchsense_cart', JSON.stringify(this.cart));
        },
        
        quickAdd(menu, event) {
            event.stopPropagation();
            if (this.cart[menu.id]) {
                this.cart[menu.id].qty += 1;
            } else {
                this.cart[menu.id] = {
                    id: menu.id,
                    name: menu.name,
                    price: parseFloat(menu.price),
                    qty: 1
                };
            }
            localStorage.setItem('lunchsense_cart', JSON.stringify(this.cart));
        },

        openDetails(menu) {
            this.activeFood = menu;
            this.quantity = 1;
            this.detailOpen = true;
        },
        
        init() {
            const savedCart = localStorage.getItem('lunchsense_cart');
            if (savedCart) {
                try {
                    this.cart = JSON.parse(savedCart);
                } catch(e) {
                    this.cart = {};
                }
            }
        }
    }" x-init="init()" class="max-w-md mx-auto px-4 py-6 pb-32 sm:max-w-7xl sm:px-6 lg:px-8 relative">

        <!-- Search Bar and Title -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-black text-text-main tracking-tight">Daily Menu</h1>
                    <p class="text-xs text-text-muted">Fresh meals prepared daily at Cafeteria DKG 6</p>
                </div>
                <a href="{{ route('home') }}" class="text-xs font-black text-primary bg-primary/10 px-3 py-1.5 rounded-full">➔ Dashboard</a>
            </div>
            
            <form action="{{ route('menus.index') }}" method="GET" class="relative">
                <input type="hidden" name="category" :value="selectedCategory">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">🔍</span>
                <input type="text" name="search" x-model="searchQuery" placeholder="Search delicious lunch meals..." 
                       class="w-full bg-white border border-gray-200/80 rounded-2xl py-3 pl-10 pr-4 text-sm font-medium text-text-main placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm">
            </form>
        </div>

        <!-- Category Chips -->
        <div class="flex gap-2 overflow-x-auto pb-4 scrollbar-none mb-4 -mx-4 px-4 sm:mx-0 sm:px-0">
            <template x-for="cat in ['Popular', 'Rice', 'Noodles', 'Beverages']">
                <button type="button" @click="selectedCategory = cat; window.location.href = '{{ route('menus.index') }}?category=' + cat + (searchQuery ? '&search=' + searchQuery : '')" 
                        :class="selectedCategory === cat ? 'bg-primary text-white shadow-soft' : 'bg-white text-text-muted hover:text-text-main border border-gray-100'"
                        class="px-5 py-2.5 rounded-full text-xs font-black transition whitespace-nowrap active:scale-95">
                    <span x-text="cat"></span>
                </button>
            </template>
        </div>

        <!-- Food Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($menus as $menu)
                <div @click="openDetails({{ json_encode($menu) }})" 
                     class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-soft hover:border-primary/20 transition duration-300 cursor-pointer group relative">
                    
                    <!-- Top Info Badges -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-col gap-1">
                            @if($menu->portions_left <= 5 && $menu->portions_left > 0)
                                <span class="bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Low Stock</span>
                            @elseif($menu->portions_left == 0)
                                <span class="bg-danger text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Sold Out</span>
                            @else
                                <span class="bg-primary/10 text-primary text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $menu->category }}</span>
                            @endif
                        </div>
                        <span class="text-xs text-text-muted font-bold">⏱️ {{ $menu->prep_time }}m</span>
                    </div>

                    <!-- Food Image / PlaceHolder -->
                    <div class="w-full h-36 rounded-2xl bg-gray-50 overflow-hidden flex items-center justify-center text-5xl mb-3 shadow-inner relative">
                        @if($menu->image)
                            <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $menu->name }}">
                        @else
                            <span>{{ match($menu->category) { 'Rice' => '🍛', 'Noodles' => '🍜', 'Beverages' => '🥤', default => '🍱' } }}</span>
                        @endif
                        <span class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                            ⭐ {{ number_format($menu->rating, 1) }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div>
                        <h3 class="font-black text-sm text-text-main leading-tight mb-1 group-hover:text-primary transition">{{ $menu->name }}</h3>
                        <p class="text-xs text-text-muted line-clamp-2 leading-relaxed mb-3">{{ $menu->description }}</p>
                    </div>

                    <!-- Action Bar -->
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-50">
                        <div>
                            <span class="text-[10px] text-text-muted block font-bold">Price</span>
                            <span class="font-black text-sm text-primary">RM {{ number_format($menu->price, 2) }}</span>
                        </div>
                        
                        @if($menu->portions_left > 0)
                            <button type="button" @click="quickAdd({{ json_encode($menu) }}, $event)" 
                                    class="w-8 h-8 rounded-full bg-primary/10 hover:bg-primary text-primary hover:text-white flex items-center justify-center font-bold text-lg transition active:scale-90">
                                +
                            </button>
                        @else
                            <span class="text-xs text-danger font-black uppercase">Out</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if(count($menus) == 0)
            <div class="bg-white rounded-3xl p-8 border border-gray-100 text-center mt-8">
                <span class="text-4xl block mb-2">🍽️</span>
                <h3 class="font-black text-sm text-text-main">No meals found</h3>
                <p class="text-xs text-text-muted mt-1">Try changing filters or search terms.</p>
            </div>
        @endif

        <!-- Floating Cart Indicator Drawer (Figma Screen 14 Bottom Trigger) -->
        <div x-show="cartCount > 0" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-20 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-20 opacity-0" 
             class="fixed bottom-24 left-4 right-4 z-40 max-w-md mx-auto sm:bottom-12 pointer-events-auto">
            <div class="bg-primary rounded-full shadow-[0_12px_40px_rgba(29,158,117,0.4)] p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-3 pl-2">
                    <span class="text-xl">🛒</span>
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-white/85" x-text="cartCount + ' Items in Cart'"></div>
                        <div class="text-sm font-black" x-text="'RM ' + cartTotal.toFixed(2)"></div>
                    </div>
                </div>
                <button type="button" @click="
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('orders.create') }}';
                    
                    // Laravel CSRF
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    
                    // Cart Data
                    const cartInput = document.createElement('input');
                    cartInput.type = 'hidden';
                    cartInput.name = 'cart_json';
                    cartInput.value = JSON.stringify(cart);
                    form.appendChild(cartInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                " class="bg-white text-primary font-black text-xs px-6 py-2.5 rounded-full hover:scale-105 active:scale-95 transition shadow-sm">
                    Checkout Now ➔
                </button>
            </div>
        </div>

        <!-- Slide-Up Food Details Modal (Figma Screen 15) -->
        <div x-show="detailOpen" class="fixed inset-0 z-50 overflow-hidden" x-cloak>
            <!-- Overlay Backdrop -->
            <div x-show="detailOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="detailOpen = false" 
                 class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <!-- Slide content container -->
            <div x-show="detailOpen" x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
                 class="absolute bottom-0 inset-x-0 bg-white rounded-t-[2.5rem] shadow-2xl p-6 pb-12 max-h-[85vh] overflow-y-auto max-w-lg mx-auto">
                
                <template x-if="activeFood">
                    <div>
                        <!-- Close & Portions Bar -->
                        <div class="flex justify-between items-center mb-4">
                            <span :class="activeFood.portions_left <= 5 ? 'bg-orange-500 text-white' : 'bg-primary/10 text-primary'" 
                                  class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider" 
                                  x-text="activeFood.portions_left + ' portions left'"></span>
                            <button type="button" @click="detailOpen = false" class="text-2xl text-text-muted font-bold p-1">&times;</button>
                        </div>

                        <!-- Food Header Image -->
                        <div class="w-full h-48 rounded-3xl bg-gray-50 overflow-hidden flex items-center justify-center text-7xl mb-4 relative shadow-inner">
                            <img x-show="activeFood.image" :src="'/storage/' + activeFood.image" class="w-full h-full object-cover" :alt="activeFood.name">
                            <span x-show="!activeFood.image" x-text="activeFood.category === 'Rice' ? '🍛' : (activeFood.category === 'Noodles' ? '🍜' : (activeFood.category === 'Beverages' ? '🥤' : '🍱'))"></span>
                        </div>

                        <!-- Basic details -->
                        <div class="flex justify-between items-start gap-4 mb-3">
                            <div>
                                <h2 class="text-xl font-black text-text-main leading-tight" x-text="activeFood.name"></h2>
                                <p class="text-xs text-text-muted mt-1" x-text="activeFood.category"></p>
                            </div>
                            <span class="text-xl font-black text-primary whitespace-nowrap" x-text="'RM ' + parseFloat(activeFood.price).toFixed(2)"></span>
                        </div>

                        <!-- Prep & Calories Row -->
                        <div class="flex gap-4 mb-4 text-xs font-bold text-text-muted">
                            <span class="flex items-center gap-1">⏱️ <span x-text="activeFood.prep_time + 'm Prep'"></span></span>
                            <span class="flex items-center gap-1">⭐ <span x-text="activeFood.rating + ' (' + activeFood.reviews_count + ' reviews)'"></span></span>
                            <span class="flex items-center gap-1">🔥 <span x-text="activeFood.calories + ' kcal'"></span></span>
                        </div>

                        <!-- AI Prediction note -->
                        <div class="bg-primary/5 rounded-2xl p-3 border border-primary/10 mb-4 flex gap-2.5 items-center">
                            <span class="text-lg">📈</span>
                            <p class="text-[11px] font-bold text-primary">
                                AI predicts this popular meal will sell out by <span class="text-danger">01:15 PM</span> today.
                            </p>
                        </div>

                        <!-- Ingredients -->
                        <div class="mb-4">
                            <h4 class="text-xs font-black uppercase text-text-main tracking-wider mb-2">Ingredients</h4>
                            <p class="text-xs text-text-muted leading-relaxed" x-text="activeFood.ingredients || 'Fresh spices, local meat, seasonings.'"></p>
                        </div>

                        <!-- Time Picker for Pickup (Figma Screen 15 slider) -->
                        <div class="mb-6">
                            <h4 class="text-xs font-black uppercase text-text-main tracking-wider mb-2">Suggested Pickup Time</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="time in ['12:30 PM', '01:00 PM', '01:30 PM']">
                                    <button type="button" @click="selectedTime = time"
                                            :class="selectedTime === time ? 'bg-primary text-white border-transparent' : 'bg-white text-text-muted border-gray-200'"
                                            class="py-2.5 border text-xs font-black rounded-xl text-center active:scale-95 transition"
                                            x-text="time"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Quantity Selector and CTA -->
                        <div class="flex gap-4 items-center">
                            <!-- Quantity -->
                            <div class="flex items-center bg-gray-100 rounded-2xl border border-gray-200 overflow-hidden">
                                <button type="button" @click="if(quantity > 1) quantity--" class="w-12 h-12 flex items-center justify-center font-black text-xl hover:bg-gray-200 transition focus:outline-none">-</button>
                                <span class="w-10 text-center font-black text-sm" x-text="quantity"></span>
                                <button type="button" @click="if(quantity < activeFood.portions_left) quantity++" class="w-12 h-12 flex items-center justify-center font-black text-xl hover:bg-gray-200 transition focus:outline-none">+</button>
                            </div>

                            <!-- CTA Button -->
                            <button type="button" @click="addToCart(activeFood)"
                                    class="flex-1 bg-primary text-white font-black text-sm h-12 rounded-2xl shadow-glow-primary hover:scale-[1.02] active:scale-[0.98] transition flex items-center justify-center gap-2">
                                Add to Cart • <span x-text="'RM ' + (activeFood.price * quantity).toFixed(2)"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>
</x-app-layout>
