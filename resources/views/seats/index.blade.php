<x-app-layout>
    <div class="max-w-md mx-auto px-4 py-6 pb-24 sm:max-w-4xl sm:px-6 lg:px-8">
        
        <!-- Case 1: Active Seat Reservation (Figma Screen 23) -->
        @if($activeReservation)
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Seat Ticket</h1>
                    <p class="text-xs text-text-muted">Your lunch spot is locked in</p>
                </div>
            </div>

            <!-- Seat Pass Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center mb-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-accent to-orange-400"></div>
                
                <span class="bg-accent/15 text-orange-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">Reserved Space</span>
                <h3 class="font-black text-2xl text-text-main mb-1">Table #{{ $activeReservation['table_number'] }}</h3>
                <p class="text-sm font-black text-primary uppercase tracking-wide mb-4">Spot: {{ $activeReservation['seat_label'] }}</p>
                
                <div class="bg-gray-50 border border-gray-100 rounded-2xl py-3 px-4 w-full flex justify-between text-xs font-bold text-text-muted mb-6">
                    <div>
                        <span class="block text-[10px] text-gray-400 uppercase">Location</span>
                        <span class="text-text-main font-black">{{ $activeReservation['zone'] }} (Window Side)</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] text-gray-400 uppercase">Valid Until</span>
                        <span class="text-text-main font-black">{{ $activeReservation['valid_until'] }}</span>
                    </div>
                </div>

                <!-- Check in QR -->
                <p class="text-xs font-black text-text-main mb-3 uppercase tracking-wide">Scan to Check-In</p>
                <div class="bg-white p-3 rounded-2xl shadow-inner border border-gray-100 flex justify-center mb-6">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate('CHECKIN-TABLE-' . $activeReservation['table_id']) !!}
                </div>

                <!-- Table Mates -->
                <div class="w-full text-left pt-4 border-t border-gray-100 mb-6">
                    <h4 class="text-xs font-black uppercase text-text-main tracking-wider mb-2">Table Mates</h4>
                    <div class="flex gap-2 flex-wrap">
                        @if(empty($activeReservation['table_mates']))
                            <span class="text-xs text-text-muted">No mates at this table yet.</span>
                        @else
                            @foreach($activeReservation['table_mates'] as $mate)
                                <span class="bg-gray-100 text-text-main text-[10px] font-bold px-2.5 py-1 rounded-full">👤 {{ $mate }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="flex gap-3 w-full">
                    <!-- Get Directions -->
                    <a href="{{ route('seats.directions') }}" class="flex-1 bg-primary text-white font-black text-xs py-3 rounded-2xl shadow-glow-primary active:scale-95 transition text-center">
                        Get Directions 📍
                    </a>

                    <!-- Release Reservation -->
                    <form action="{{ route('seats.release') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-gray-50 border border-gray-200 hover:bg-gray-100 text-text-muted hover:text-text-main font-black text-xs py-3 rounded-2xl active:scale-95 transition text-center">
                            Release Seat
                        </button>
                    </form>
                </div>
            </div>

        <!-- Case 2: Smart Seat Finder Map (Figma Screen 22) -->
        @else
            <div x-data="{
                socialMode: false,
                selectedTable: null,
                
                tableMatchesFilter(table) {
                    if (this.socialMode) {
                        return table.social_mode;
                    }
                    return true;
                },
                
                selectTable(table) {
                    this.selectedTable = table;
                }
            }" class="relative">

                <!-- Header area -->
                <div class="mb-6">
                    <h1 class="text-2xl font-black text-text-main tracking-tight font-sans">Smart Seat Finder</h1>
                    <p class="text-xs text-text-muted">Locate and book available seats in real-time</p>
                </div>

                @if(session('success'))
                    <div class="bg-primary text-white text-xs font-bold p-4 rounded-2xl mb-4 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-danger text-white text-xs font-bold p-4 rounded-2xl mb-4 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Circular occupancy widgets -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 mb-6 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full border-4 border-primary/20 border-t-primary flex items-center justify-center font-black text-xs text-primary animate-spin-slow">
                            {{ round(($availableSeatsCount / max(1, $totalSeats)) * 100) }}%
                        </div>
                        <div>
                            <span class="text-[10px] text-text-muted font-bold block uppercase tracking-wider">Live Capacity</span>
                            <span class="text-sm font-black text-text-main" x-text="'{{ $availableSeatsCount }}/{{ $totalSeats }} seats free'"></span>
                        </div>
                    </div>
                    
                    <!-- Social Mode Toggle -->
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-text-muted font-black uppercase">Social Mode</span>
                        <button type="button" @click="socialMode = !socialMode" 
                                :class="socialMode ? 'bg-primary justify-end' : 'bg-gray-200 justify-start'"
                                class="w-10 h-6 rounded-full p-0.5 flex items-center transition duration-300">
                            <span class="w-5 h-5 rounded-full bg-white shadow-md"></span>
                        </button>
                    </div>
                </div>

                <!-- Visual Seating Map Layout Grid -->
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 mb-6">
                    <h3 class="text-xs font-black text-text-main uppercase tracking-wider mb-4 text-center">DKG 6 Seating Layout Map</h3>
                    
                    <div class="grid grid-cols-2 gap-6 md:grid-cols-2">
                        <!-- Zone A -->
                        <div class="bg-gray-50 rounded-3xl p-4 border border-dashed border-gray-200">
                            <h4 class="text-[10px] font-black uppercase text-text-muted mb-4 text-center">Zone A (General)</h4>
                            
                            <div class="grid grid-cols-2 gap-3 justify-center">
                                @foreach($seats->where('zone', 'Zone A') as $seat)
                                    <button type="button" @click="selectTable({{ json_encode($seat) }})"
                                            :class="[
                                                selectedTable && selectedTable.id === {{ $seat->id }} ? 'ring-2 ring-primary scale-105' : '',
                                                socialMode && !{{ $seat->social_mode ? 'true' : 'false' }} ? 'opacity-30' : 'opacity-100'
                                            ]"
                                            class="aspect-square rounded-2xl flex flex-col justify-center items-center gap-1 transition shadow-sm
                                                {{ $seat->status === 'full' ? 'bg-red-50 text-danger border border-red-200' : '' }}
                                                {{ $seat->status === 'cleaning' ? 'bg-orange-50 text-accent border border-orange-200' : '' }}
                                                {{ $seat->status === 'available' && !$seat->social_mode ? 'bg-emerald-50 text-primary border border-emerald-200' : '' }}
                                                {{ $seat->status === 'available' && $seat->social_mode ? 'bg-blue-50 text-blue-600 border border-blue-200 border-dashed' : '' }}
                                            ">
                                        <span class="text-xs font-black">#{{ $seat->table_number }}</span>
                                        <span class="text-[8px] font-bold" x-text="socialMode && {{ $seat->social_mode ? 'true' : 'false' }} ? 'SOCIAL' : '{{ $seat->available_seats }}/{{ $seat->capacity }}'"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Zone B -->
                        <div class="bg-gray-50 rounded-3xl p-4 border border-dashed border-gray-200">
                            <h4 class="text-[10px] font-black uppercase text-text-muted mb-4 text-center">Zone B (Window Side)</h4>
                            
                            <div class="grid grid-cols-2 gap-3 justify-center">
                                @foreach($seats->where('zone', 'Zone B') as $seat)
                                    <button type="button" @click="selectTable({{ json_encode($seat) }})"
                                            :class="[
                                                selectedTable && selectedTable.id === {{ $seat->id }} ? 'ring-2 ring-primary scale-105' : '',
                                                socialMode && !{{ $seat->social_mode ? 'true' : 'false' }} ? 'opacity-30' : 'opacity-100'
                                            ]"
                                            class="aspect-square rounded-2xl flex flex-col justify-center items-center gap-1 transition shadow-sm
                                                {{ $seat->status === 'full' ? 'bg-red-50 text-danger border border-red-200' : '' }}
                                                {{ $seat->status === 'cleaning' ? 'bg-orange-50 text-accent border border-orange-200' : '' }}
                                                {{ $seat->status === 'available' && !$seat->social_mode ? 'bg-emerald-50 text-primary border border-emerald-200' : '' }}
                                                {{ $seat->status === 'available' && $seat->social_mode ? 'bg-blue-50 text-blue-600 border border-blue-200 border-dashed' : '' }}
                                            ">
                                        <span class="text-xs font-black">#{{ $seat->table_number }}</span>
                                        <span class="text-[8px] font-bold" x-text="socialMode && {{ $seat->social_mode ? 'true' : 'false' }} ? 'SOCIAL' : '{{ $seat->available_seats }}/{{ $seat->capacity }}'"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Map Map Legends -->
                    <div class="flex justify-around items-center mt-5 pt-4 border-t border-gray-100 text-[9px] font-bold text-text-muted">
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-emerald-100 border border-emerald-300 rounded-md"></span> Free</span>
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-red-100 border border-red-300 rounded-md"></span> Full</span>
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-orange-100 border border-orange-300 rounded-md"></span> Cleaning</span>
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-blue-100 border border-blue-300 rounded-md border-dashed"></span> Social</span>
                    </div>
                </div>

                <!-- AI Seating strategy recommendations -->
                <div class="bg-primary/5 border border-primary/10 rounded-2xl p-4 mb-6 flex gap-2.5 items-center">
                    <span class="text-base">💡</span>
                    <div>
                        <h4 class="text-xs font-black text-primary uppercase tracking-wider">AI Seating Strategy</h4>
                        <p class="text-[10px] text-text-main font-medium leading-relaxed mt-0.5">
                            Looking to study or network? We recommend selecting <strong class="font-bold">Table #12</strong> in Zone B which is set to social mode.
                        </p>
                    </div>
                </div>

                <!-- Active Seating Booking Detail Panel -->
                <div x-show="selectedTable" x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="translate-y-12 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                     class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                    
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span class="text-[10px] text-text-muted font-bold uppercase tracking-wider" x-text="selectedTable ? selectedTable.zone : ''"></span>
                            <h3 class="font-black text-lg text-text-main" x-text="selectedTable ? 'Table #' + selectedTable.table_number : ''"></h3>
                        </div>
                        <template x-if="selectedTable">
                            <span :class="[
                                selectedTable.status === 'available' ? 'bg-primary/10 text-primary' : '',
                                selectedTable.status === 'full' ? 'bg-red-100 text-danger' : '',
                                selectedTable.status === 'cleaning' ? 'bg-orange-100 text-accent' : ''
                            ]" class="text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider" x-text="selectedTable.status"></span>
                        </template>
                    </div>

                    <p class="text-xs text-text-muted mb-4" x-text="selectedTable ? 'Seats Capacity: ' + selectedTable.available_seats + '/' + selectedTable.capacity + ' free' : ''"></p>

                    <!-- Table Mates checklist if Social Table -->
                    <template x-if="selectedTable && selectedTable.social_mode">
                        <div class="mb-4 pt-3 border-t border-gray-50">
                            <h4 class="text-[10px] font-black uppercase text-text-main tracking-wider mb-2">Current Table Mates</h4>
                            <div class="flex gap-1.5 flex-wrap">
                                <template x-for="user in (selectedTable.current_users || [])">
                                    <span class="bg-gray-100 text-text-main text-[9px] font-bold px-2 py-0.5 rounded-full" x-text="'👤 ' + user"></span>
                                </template>
                                <template x-if="(!selectedTable.current_users || selectedTable.current_users.length === 0)">
                                    <span class="text-[10px] text-text-muted">Table is empty. Start the social mode!</span>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Join CTA Form action -->
                    <template x-if="selectedTable && selectedTable.status === 'available' && selectedTable.available_seats > 0">
                        <form :action="'/seats/' + selectedTable.id + '/reserve'" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-primary text-white font-black text-xs py-3 rounded-2xl hover:scale-[1.01] active:scale-[0.99] transition shadow-glow-primary text-center">
                                Join & Reserve a Seat ➔
                            </button>
                        </form>
                    </template>

                    <template x-if="selectedTable && (selectedTable.status !== 'available' || selectedTable.available_seats === 0)">
                        <button type="button" disabled class="w-full bg-gray-200 text-gray-500 font-black text-xs py-3 rounded-2xl cursor-not-allowed text-center">
                            Table Unavailable
                        </button>
                    </template>
                </div>

            </div>
        @endif

    </div>
</x-app-layout>
