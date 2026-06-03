<x-app-layout>
    <div x-data="{
        cart: {},
        paymentMethod: 'LunchSense Wallet',
        serviceFee: 0.50,
        userWalletBalance: {{ Auth::user()->wallet_balance }},
        
        get cartTotal() {
            let total = 0;
            for (let id in this.cart) {
                total += this.cart[id].price * this.cart[id].qty;
            }
            return total;
        },
        
        get grandTotal() {
            return this.cartTotal > 0 ? this.cartTotal + this.serviceFee : 0;
        },
        
        get hasSufficientBalance() {
            if (this.paymentMethod !== 'LunchSense Wallet') return true;
            return this.userWalletBalance >= this.grandTotal;
        },

        removeItem(id) {
            delete this.cart[id];
            localStorage.setItem('lunchsense_cart', JSON.stringify(this.cart));
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
    }" x-init="init()" class="max-w-md mx-auto px-4 py-6 pb-32 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('menus.index') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:scale-105 active:scale-95 transition">
                <span class="text-sm">⬅️</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight">Checkout</h1>
                <p class="text-xs text-text-muted">Confirm order items and payment method</p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl p-4 mb-4 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            
            <!-- Hidden inputs container for cart items -->
            <template x-for="(item, id) in cart" :key="id">
                <input type="hidden" :name="'items[' + id + ']'" :value="item.qty">
            </template>
            <input type="hidden" name="payment_method" :value="paymentMethod">

            <!-- 1. Order Summary -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">1. Order Summary</h3>
                
                <div class="space-y-4">
                    <template x-for="(item, id) in cart" :key="id">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <h4 class="text-sm font-black text-text-main" x-text="item.name"></h4>
                                <p class="text-[10px] text-text-muted mt-0.5" x-text="'Qty: ' + item.qty + ' x RM ' + parseFloat(item.price).toFixed(2)"></p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black text-primary" x-text="'RM ' + (item.price * item.qty).toFixed(2)"></span>
                                <button type="button" @click="removeItem(id)" class="text-gray-400 hover:text-red-500 transition text-sm">🗑️</button>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="Object.keys(cart).length === 0" class="text-center py-6">
                        <span class="text-3xl block mb-2">🛒</span>
                        <p class="text-xs text-text-muted font-bold">Your cart is empty.</p>
                        <a href="{{ route('menus.index') }}" class="text-xs text-primary font-black mt-2 inline-block">Browse Menu</a>
                    </div>
                </div>
            </div>

            <!-- 2. Time Picker -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6" x-show="Object.keys(cart).length > 0">
                <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-2">2. Pickup Time</h3>
                <div class="bg-primary/5 rounded-2xl p-3 border border-primary/10 mb-4 flex gap-2 items-center">
                    <span class="text-base">🕒</span>
                    <p class="text-[11px] font-bold text-primary">
                        Recommended Pickup: <span class="underline">12:45 PM</span> to skip the crowd queue.
                    </p>
                </div>
                
                <x-time-picker name="pickup_time" />
                @error('pickup_time')
                    <p class="text-danger text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Payment Method -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6" x-show="Object.keys(cart).length > 0">
                <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">3. Select Payment Method</h3>
                
                <div class="space-y-3">
                    <!-- LunchSense Wallet -->
                    <label class="flex justify-between items-center p-4 border rounded-2xl cursor-pointer transition active:scale-[0.99]" 
                           :class="paymentMethod === 'LunchSense Wallet' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_opt" value="LunchSense Wallet" x-model="paymentMethod" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            <div>
                                <span class="text-sm font-black text-text-main">LunchSense Digital Wallet</span>
                                <span class="text-[10px] text-text-muted block mt-0.5" x-text="'Balance: RM ' + userWalletBalance.toFixed(2)"></span>
                            </div>
                        </div>
                        <span class="text-base">💳</span>
                    </label>

                    <!-- DuitNow QR -->
                    <label class="flex justify-between items-center p-4 border rounded-2xl cursor-pointer transition active:scale-[0.99]" 
                           :class="paymentMethod === 'DuitNow QR' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_opt" value="DuitNow QR" x-model="paymentMethod" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            <div>
                                <span class="text-sm font-black text-text-main">DuitNow QR</span>
                                <span class="text-[10px] text-text-muted block mt-0.5">Pay via bank app or e-wallet</span>
                            </div>
                        </div>
                        <span class="text-base">📱</span>
                    </label>

                    <!-- Touch 'n Go -->
                    <label class="flex justify-between items-center p-4 border rounded-2xl cursor-pointer transition active:scale-[0.99]" 
                           :class="paymentMethod === 'Touch n Go' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_opt" value="Touch n Go" x-model="paymentMethod" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            <div>
                                <span class="text-sm font-black text-text-main">Touch 'n Go eWallet</span>
                                <span class="text-[10px] text-text-muted block mt-0.5">Fast electronic checkouts</span>
                            </div>
                        </div>
                        <span class="text-base">🔵</span>
                    </label>
                </div>
            </div>

            <!-- 4. Price Breakdown -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6" x-show="Object.keys(cart).length > 0">
                <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">Summary Breakdown</h3>
                
                <div class="space-y-3 text-xs font-bold text-text-muted">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="text-text-main" x-text="'RM ' + cartTotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Service Fee</span>
                        <span class="text-text-main" x-text="'RM ' + serviceFee.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-sm font-black pt-3 border-t border-gray-50 text-text-main">
                        <span>Total Price</span>
                        <span class="text-primary" x-text="'RM ' + grandTotal.toFixed(2)"></span>
                    </div>
                </div>
            </div>

            <!-- Bottom Floating CTA -->
            <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-100 shadow-[0_-10px_40px_rgb(0,0,0,0.05)] z-40" x-show="Object.keys(cart).length > 0">
                <div class="max-w-md mx-auto flex justify-between items-center">
                    <div>
                        <span class="text-[10px] text-text-muted font-bold block uppercase tracking-wider">Total Charge</span>
                        <span class="text-lg font-black text-primary" x-text="'RM ' + grandTotal.toFixed(2)"></span>
                    </div>
                    
                    <button type="submit" @click="localStorage.removeItem('lunchsense_cart')" :disabled="!hasSufficientBalance"
                            :class="hasSufficientBalance ? 'bg-primary text-white hover:scale-105 active:scale-95 shadow-glow-primary' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                            class="font-black text-sm px-8 py-3.5 rounded-2xl transition flex items-center gap-2">
                        <span x-text="hasSufficientBalance ? 'Confirm & Pay' : 'Insufficient Balance'"></span> ➔
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>
