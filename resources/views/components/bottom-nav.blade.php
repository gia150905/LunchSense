<div class="fixed bottom-0 left-0 right-0 sm:hidden z-50 px-4 pb-6 pt-2 pointer-events-none">
    <div class="bg-white/70 backdrop-blur-xl rounded-full shadow-glow-primary border border-white/40 flex justify-around items-center py-3 px-2 pointer-events-auto">
        
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 w-14 active:scale-90 transition-transform {{ request()->routeIs('home') ? 'text-primary' : 'text-text-muted hover:text-text-main' }}">
            <span class="text-2xl transition-transform {{ request()->routeIs('home') ? 'scale-110 -translate-y-1 font-bold' : '' }}">🏠</span>
            <span class="text-[10px] font-bold">Home</span>
        </a>
        
        <a href="{{ route('menus.index') }}" class="flex flex-col items-center gap-1 w-14 active:scale-90 transition-transform {{ request()->routeIs('menus.index') || request()->routeIs('orders.create') || request()->routeIs('orders.show') ? 'text-primary' : 'text-text-muted hover:text-text-main' }}">
            <span class="text-2xl transition-transform {{ request()->routeIs('menus.index') || request()->routeIs('orders.create') || request()->routeIs('orders.show') ? 'scale-110 -translate-y-1 font-bold' : '' }}">🍔</span>
            <span class="text-[10px] font-bold">Menu</span>
        </a>
        
        <a href="{{ route('orders.index') }}" class="flex flex-col items-center gap-1 w-14 active:scale-90 transition-transform {{ request()->routeIs('orders.index') ? 'text-primary' : 'text-text-muted hover:text-text-main' }}">
            <span class="text-2xl transition-transform {{ request()->routeIs('orders.index') ? 'scale-110 -translate-y-1 font-bold' : '' }}">🧾</span>
            <span class="text-[10px] font-bold">Orders</span>
        </a>
        
        <a href="{{ route('seats.index') }}" class="flex flex-col items-center gap-1 w-14 active:scale-90 transition-transform {{ request()->routeIs('seats.*') ? 'text-primary' : 'text-text-muted hover:text-text-main' }}">
            <span class="text-2xl transition-transform {{ request()->routeIs('seats.*') ? 'scale-110 -translate-y-1 font-bold' : '' }}">🪑</span>
            <span class="text-[10px] font-bold">Seats</span>
        </a>
        
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 w-14 active:scale-90 transition-transform {{ request()->routeIs('profile.*') || request()->routeIs('wallet.*') ? 'text-primary' : 'text-text-muted hover:text-text-main' }}">
            <span class="text-2xl transition-transform {{ request()->routeIs('profile.*') || request()->routeIs('wallet.*') ? 'scale-110 -translate-y-1 font-bold' : '' }}">😎</span>
            <span class="text-[10px] font-bold">Profile</span>
        </a>
        
    </div>
</div>

<!-- Spacer to prevent content from hiding behind the bottom nav on mobile -->
<div class="h-24 sm:hidden"></div>
