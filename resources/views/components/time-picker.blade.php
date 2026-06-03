@props(['name' => 'pickup_time', 'slots' => ['11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '01:00 PM', '01:30 PM', '02:00 PM']])

<div x-data="{ selected: '' }" class="mb-4">
    <input type="hidden" name="{{ $name }}" x-model="selected" required>
    
    <div class="flex overflow-x-auto pb-4 gap-3 snap-x scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
        @foreach($slots as $slot)
            @php 
                $timeValue = \Carbon\Carbon::parse($slot)->format('H:i'); 
            @endphp
            <button 
                type="button" 
                @click="selected = '{{ $timeValue }}'"
                :class="{
                    'bg-primary text-white shadow-md border-transparent scale-105': selected === '{{ $timeValue }}', 
                    'bg-white border-gray-200 text-gray-600 hover:border-primary hover:text-primary': selected !== '{{ $timeValue }}'
                }"
                class="snap-start shrink-0 px-6 py-4 rounded-2xl border-2 font-bold transition-all text-sm whitespace-nowrap focus:outline-none"
            >
                <div class="flex flex-col items-center gap-1">
                    <span class="text-lg">🕒</span>
                    <span>{{ $slot }}</span>
                </div>
            </button>
        @endforeach
    </div>
    
    <p x-show="!selected" class="text-sm text-accent font-medium flex items-center gap-1 mt-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        Please select a pickup time
    </p>
</div>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
