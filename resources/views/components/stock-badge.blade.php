@props(['status', 'stock' => null])

@php
    $statusClass = match($status) {
        'available' => 'bg-primary bg-opacity-20 text-primary',
        'almost_sold_out' => 'bg-accent bg-opacity-30 text-yellow-800',
        'sold_out' => 'bg-danger bg-opacity-20 text-danger',
        default => 'bg-gray-100 text-gray-700'
    };
    $label = ucfirst(str_replace('_', ' ', $status));
@endphp

<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
    @if($status == 'available')
        <div class="w-2 h-2 rounded-full bg-primary"></div>
    @elseif($status == 'almost_sold_out')
        <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
    @elseif($status == 'sold_out')
        <div class="w-2 h-2 rounded-full bg-danger"></div>
    @endif
    
    <span>{{ $label }}</span>
    
    @if($stock > 0 && $status != 'sold_out')
        <span class="opacity-75 font-medium ml-1">&bull; {{ $stock }} left</span>
    @endif
</div>
