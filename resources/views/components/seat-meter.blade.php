@props(['seat'])

@php
    $percentage = ($seat->capacity > 0) ? round((($seat->capacity - $seat->available_seats) / $seat->capacity) * 100) : 100;
    
    $colorClass = match($seat->status) {
        'available' => 'bg-primary',
        'full' => 'bg-danger',
        'cleaning' => 'bg-accent',
        default => 'bg-gray-400'
    };
    
    $bgClass = match($seat->status) {
        'available' => 'bg-primary bg-opacity-10',
        'full' => 'bg-danger bg-opacity-10',
        'cleaning' => 'bg-accent bg-opacity-10',
        default => 'bg-gray-100'
    };
    
    $borderClass = match($seat->status) {
        'available' => 'border-primary',
        'full' => 'border-danger',
        'cleaning' => 'border-accent',
        default => 'border-gray-200'
    };
    
    $textClass = match($seat->status) {
        'available' => 'text-primary',
        'full' => 'text-danger',
        'cleaning' => 'text-yellow-700',
        default => 'text-gray-500'
    };
@endphp

<div class="p-5 rounded-2xl border-2 {{ $bgClass }} border-opacity-20 {{ $borderClass }} transition-transform hover:-translate-y-1 hover:shadow-lg bg-white relative overflow-hidden">
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-xl font-black text-text-main flex items-center gap-2">
            🪑 Table {{ $seat->table_number }}
        </h3>
        <span class="text-xs font-bold px-2 py-1 rounded-lg {{ $bgClass }} {{ $textClass }}">
            {{ ucfirst($seat->status) }}
        </span>
    </div>
    
    <div class="relative w-full h-3 bg-gray-100 rounded-full overflow-hidden mb-2">
        <div class="absolute top-0 left-0 h-full {{ $colorClass }} rounded-full transition-all duration-500 ease-out" style="width: {{ $percentage }}%"></div>
    </div>
    
    <div class="mt-2 text-sm text-gray-500 font-medium flex justify-between items-center">
        <span>Occupancy</span>
        <span class="font-bold text-text-main bg-gray-100 px-2 py-0.5 rounded-md">{{ $seat->capacity - $seat->available_seats }}/{{ $seat->capacity }}</span>
    </div>
</div>
