@props(['status'])

@php
    $steps = [
        'waiting' => ['label' => 'Confirmed', 'icon' => '📝'],
        'preparing' => ['label' => 'Preparing', 'icon' => '🍳'],
        'ready' => ['label' => 'Ready to Pickup', 'icon' => '🛍️'],
        'completed' => ['label' => 'Completed', 'icon' => '✅'],
    ];
    
    $currentIndex = array_search($status, array_keys($steps));
@endphp

<div class="relative flex justify-between w-full max-w-2xl mx-auto px-4 mt-8 mb-12">
    <!-- Connecting Line -->
    <div class="absolute top-6 left-[10%] right-[10%] h-1 bg-gray-200 -translate-y-1/2 rounded-full z-0"></div>
    
    <!-- Active Line -->
    <div class="absolute top-6 left-[10%] h-1 bg-primary -translate-y-1/2 rounded-full z-0 transition-all duration-500"
         style="width: {{ ($currentIndex / (count($steps) - 1)) * 80 }}%;"></div>

    @foreach($steps as $key => $step)
        @php
            $stepIndex = $loop->index;
            $isActive = $stepIndex <= $currentIndex;
            $isCurrent = $stepIndex === $currentIndex;
        @endphp
        
        <div class="relative z-10 flex flex-col items-center gap-2">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-md transition-all duration-300 {{ $isActive ? 'bg-primary text-white scale-110' : 'bg-white border-2 border-gray-200 text-gray-400 grayscale' }} {{ $isCurrent ? 'ring-4 ring-primary ring-opacity-30' : '' }}">
                {{ $step['icon'] }}
            </div>
            <span class="text-xs font-bold whitespace-nowrap {{ $isActive ? 'text-primary' : 'text-gray-400' }}">
                {{ $step['label'] }}
            </span>
        </div>
    @endforeach
</div>
