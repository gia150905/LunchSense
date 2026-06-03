<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <!-- Recreated LunchSense Clock-Fork-Leaf Logo in clean vector SVG -->
    <div class="relative w-12 h-12 flex-shrink-0">
        <svg class="w-full h-full" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- 3 Orange Speed Marks (top-left) -->
            <path d="M15 42 L25 36" stroke="#F59E0B" stroke-width="6" stroke-linecap="round"/>
            <path d="M22 25 L34 23" stroke="#F59E0B" stroke-width="6" stroke-linecap="round"/>
            <path d="M38 14 L46 25" stroke="#F59E0B" stroke-width="6" stroke-linecap="round"/>

            <!-- Clock Outer Circle -->
            <circle cx="65" cy="65" r="42" stroke="#1D9E75" stroke-width="7" stroke-linecap="round"/>

            <!-- Clock Ticks -->
            <line x1="65" y1="30" x2="65" y2="36" stroke="#0F5132" stroke-width="4" stroke-linecap="round"/>
            <line x1="65" y1="94" x2="65" y2="100" stroke="#0F5132" stroke-width="4" stroke-linecap="round"/>
            <line x1="30" y1="65" x2="36" y2="65" stroke="#0F5132" stroke-width="4" stroke-linecap="round"/>

            <!-- Fork Clock Hand (Hour hand pointing at 2 o'clock) -->
            <g transform="translate(65,65) rotate(45) translate(-65,-65)">
                <!-- Stem -->
                <line x1="65" y1="65" x2="65" y2="28" stroke="#0F5132" stroke-width="5" stroke-linecap="round"/>
                <!-- Fork Base -->
                <path d="M60 28 C60 24, 70 24, 70 28" fill="#0F5132"/>
                <!-- Prongs -->
                <path d="M60 24 V15 C60 14, 62 13, 62 15 V24 M65 20 V13 C65 12, 66 12, 66 13 V20 M70 24 V15 C70 14, 68 13, 68 15 V24" stroke="#0F5132" stroke-width="2.5" stroke-linecap="round" fill="none"/>
            </g>

            <!-- Minute Hand (vertical-ish) -->
            <line x1="65" y1="65" x2="65" y2="40" stroke="#0F5132" stroke-width="6" stroke-linecap="round"/>

            <!-- Center Clock Pin -->
            <circle cx="65" cy="65" r="6" fill="#0F5132"/>
            <circle cx="65" cy="65" r="2.5" fill="#FFF"/>

            <!-- Green Leaf inside (bottom-right area) -->
            <path d="M65 65 C80 85, 95 85, 96 68 C94 88, 78 95, 65 65" fill="#1D9E75"/>
        </svg>
    </div>

    <!-- Brand Typography matching your mockup -->
    <div class="flex flex-col select-none">
        <h2 class="text-xl font-black tracking-tight text-emerald-950 leading-none">
            Lunch<span class="text-primary-light text-primary">Sense</span>
        </h2>
        <span class="text-[8px] font-black tracking-widest text-text-muted/80 uppercase mt-1">
            - Know Before You Go -
        </span>
    </div>
</div>
