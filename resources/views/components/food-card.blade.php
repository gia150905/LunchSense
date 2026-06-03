@props(['menu'])

<div class="bg-white/80 backdrop-blur-md rounded-3xl p-3 shadow-soft hover:shadow-glow-primary transition-all duration-300 transform hover:-translate-y-2 border border-white flex flex-col h-full relative group active:scale-95 cursor-pointer">
    
    <div class="relative w-full h-48 rounded-2xl overflow-hidden mb-4 bg-gray-50 group-hover:scale-[1.02] transition-transform">
        @if($menu->image)
            <img src="{{ asset('storage/'.$menu->image) }}" class="w-full h-full object-cover" alt="{{ $menu->name }}">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                <span class="text-5xl mb-2">🍱</span>
            </div>
        @endif
        
        <div class="absolute top-3 left-3 shadow-md rounded-full bg-white bg-opacity-90 backdrop-blur-sm">
            <x-stock-badge :status="$menu->status" :stock="$menu->stock" />
        </div>
    </div>
    
    <div class="flex-1 flex flex-col justify-between px-2">
        <div>
            <h4 class="font-black text-xl text-text-main line-clamp-1 mb-1">{{ $menu->name }}</h4>
            @if($menu->description)
                <p class="text-sm text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $menu->description }}</p>
            @endif
        </div>
        
        <div class="mt-auto pt-3 flex justify-between items-center">
            <p class="text-2xl font-black text-primary tracking-tight"><span class="text-sm font-bold text-gray-400 mr-1">RM</span>{{ number_format($menu->price, 2) }}</p>
        </div>
    </div>
</div>
