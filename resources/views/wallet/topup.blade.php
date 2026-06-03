<x-app-layout>
    <div x-data="{
        amount: '20.00',
        paymentMethod: 'DuitNow QR',
        setAmount(val) {
            this.amount = parseFloat(val).toFixed(2);
        }
    }" class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:scale-105 active:scale-95 transition">
                <span class="text-sm">⬅️</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Wallet Top-Up</h1>
                <p class="text-xs text-text-muted">Instantly top up your LunchSense digital wallet balance</p>
            </div>
        </div>

        @if(session('success'))
            <!-- Success message -->
            <div class="bg-primary rounded-3xl p-6 text-white text-center shadow-soft mb-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-lg"></div>
                <div class="text-4xl mb-2">⚡</div>
                <h2 class="text-lg font-black">{{ session('success') }}</h2>
                <p class="text-xs text-white/90 mt-1 font-medium font-mono">Your balance has been updated instantly.</p>
            </div>
        @endif

        <!-- Balance card -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6 flex justify-between items-center relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 text-7xl opacity-5 pointer-events-none">💳</div>
            <div>
                <span class="text-[10px] text-text-muted font-bold block uppercase tracking-wider">Current Wallet Balance</span>
                <span class="text-3xl font-black text-primary mt-1">RM {{ number_format($user->wallet_balance, 2) }}</span>
            </div>
            <a href="{{ route('wallet.history') }}" class="text-xs font-black text-primary bg-primary/10 px-3.5 py-2 rounded-xl">View History ➔</a>
        </div>

        <form action="{{ route('wallet.checkout') }}" method="POST">
            @csrf
            
            <!-- Amount selection panel -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">Select Top-Up Amount</h3>
                
                <!-- Custom Value input -->
                <div class="relative mb-6">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-black text-lg text-text-main">RM</span>
                    <input type="number" step="0.01" min="1" name="amount" x-model="amount" required
                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 pl-12 pr-4 font-black text-xl text-text-main focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition shadow-inner">
                </div>

                <!-- Presets -->
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" @click="setAmount(10)" 
                            :class="parseFloat(amount) === 10 ? 'bg-primary text-white border-transparent' : 'bg-white border-gray-200 text-text-muted hover:border-primary hover:text-primary'"
                            class="py-3 border-2 font-black rounded-xl text-xs text-center active:scale-95 transition">
                        +10
                    </button>
                    <button type="button" @click="setAmount(20)" 
                            :class="parseFloat(amount) === 20 ? 'bg-primary text-white border-transparent' : 'bg-white border-gray-200 text-text-muted hover:border-primary hover:text-primary'"
                            class="py-3 border-2 font-black rounded-xl text-xs text-center active:scale-95 transition">
                        +20
                    </button>
                    <button type="button" @click="setAmount(50)" 
                            :class="parseFloat(amount) === 50 ? 'bg-primary text-white border-transparent' : 'bg-white border-gray-200 text-text-muted hover:border-primary hover:text-primary'"
                            class="py-3 border-2 font-black rounded-xl text-xs text-center active:scale-95 transition">
                        +50
                    </button>
                    <button type="button" @click="setAmount(100)" 
                            :class="parseFloat(amount) === 100 ? 'bg-primary text-white border-transparent' : 'bg-white border-gray-200 text-text-muted hover:border-primary hover:text-primary'"
                            class="py-3 border-2 font-black rounded-xl text-xs text-center active:scale-95 transition">
                        +100
                    </button>
                </div>
            </div>

            <!-- Payment gateway selection -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-4">Payment Processor</h3>
                
                <input type="hidden" name="payment_method" :value="paymentMethod">
                <div class="space-y-3">
                    <label class="flex justify-between items-center p-4 border rounded-2xl cursor-pointer transition active:scale-[0.99]" 
                           :class="paymentMethod === 'DuitNow QR' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="pay_gateway" value="DuitNow QR" x-model="paymentMethod" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            <span class="text-sm font-black text-text-main">DuitNow QR (Instant payment)</span>
                        </div>
                        <span class="text-base">📱</span>
                    </label>

                    <label class="flex justify-between items-center p-4 border rounded-2xl cursor-pointer transition active:scale-[0.99]" 
                           :class="paymentMethod === 'Touch n Go' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="pay_gateway" value="Touch n Go" x-model="paymentMethod" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            <span class="text-sm font-black text-text-main">Touch 'n Go eWallet</span>
                        </div>
                        <span class="text-base">🔵</span>
                    </label>

                    <label class="flex justify-between items-center p-4 border rounded-2xl cursor-pointer transition active:scale-[0.99]" 
                           :class="paymentMethod === 'Credit Card' ? 'border-primary bg-primary/5' : 'border-gray-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="pay_gateway" value="Credit Card" x-model="paymentMethod" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                            <span class="text-sm font-black text-text-main">Credit / Debit Card</span>
                        </div>
                        <span class="text-base">💳</span>
                    </label>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="w-full bg-primary text-white font-black text-sm py-4 rounded-2xl shadow-glow-primary hover:scale-[1.01] active:scale-[0.99] transition flex items-center justify-center gap-2">
                Top Up Now ➔
            </button>
        </form>

    </div>
</x-app-layout>
