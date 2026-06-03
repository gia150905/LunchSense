<x-app-layout>
    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-3xl sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('wallet.topup') }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center hover:scale-105 active:scale-95 transition">
                <span class="text-sm">⬅️</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Transaction History</h1>
                <p class="text-xs text-text-muted">Digital wallet activity logs and billing references</p>
            </div>
        </div>

        <!-- History listing card -->
        <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-gray-100">
            <div class="space-y-4">
                @forelse($logs as $log)
                    <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-black text-text-main">{{ $log['type'] }}</h4>
                                <span class="text-[9px] font-black px-2 py-0.5 bg-emerald-50 text-primary rounded-full uppercase tracking-wider">{{ $log['status'] }}</span>
                            </div>
                            <p class="text-[10px] text-text-muted mt-1">Ref: <span class="font-mono">{{ $log['reference'] }}</span> • {{ $log['date'] }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black {{ $log['amount'] > 0 ? 'text-primary' : 'text-text-main' }}">
                                {{ $log['amount'] > 0 ? '+' : '' }}RM {{ number_format(abs($log['amount']), 2) }}
                            </span>
                            <span class="block text-[8px] text-text-muted mt-0.5 font-bold">{{ $log['payment_method'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="text-3xl block mb-2">📜</span>
                        <p class="text-xs text-text-muted font-bold">No transactions found.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
