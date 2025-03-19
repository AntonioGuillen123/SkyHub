<x-app-layout>
    <div class="py-12 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden flex flex-wrap justify-center">
                <div class="flex flex-col gap-4 w-4/5 md:w-2/3">
                    @forelse ($airplanes as $airplaneName => $airplane)
                        @forelse ($airplane as $maximumPlaces => $flights)
                            @php
                                $airplaneId = $flights[0]->airplane_id ?? null;
                            @endphp

                            <div x-data="{ open: false }" class="bg-white rounded-xl">
                                <div class="flex items-center justify-between p-2 cursor-pointer" @click="open = !open">
                                    <div class="flex justify-center font-bold">Airplane #{{ $airplaneId }}</div>
                                    <div class="flex justify-center font-bold w-32">{{ $airplaneName }}</div>
                                    <div class="flex justify-center font-bold">{{ $maximumPlaces }}</div>
                                    <div class="cursor-pointer">
                                        <svg class="fill-current h-8 w-8 transition-transform duration-200"
                                            :class="open ? 'rotate-180' : 'rotate-0'" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="open" x-collapse class="bg-gray-100 p-2 rounded-b-xl">
                                    <div class="p-2 text-gray-800 font-semibold">🛫 Flights:</div>
                                    <ul class="space-y-2">
                                        @forelse ($flights as $flight)
                                            @php
                                                $flightId = $flight->id;
                                                $departure = $flight->journey->destinationDeparture->name;
                                                $arrival = $flight->journey->destinationArrival->name;
                                                $departureDate = date('Y-m-d H:i', strtotime($flight->flight_date));
                                                $arrivalDate = date('Y-m-d H:i', strtotime($flight->arrival_date));
                                                $users = $flight->users;
                                            @endphp

                                            <li x-data="{ openFlight: false }" class="p-2 bg-white shadow-sm rounded-md">
                                                <div class="flex justify-between items-center text-xs md:text-sm cursor-pointer"
                                                    @click="openFlight = !openFlight">
                                                    <span class="font-bold">✈️ Flight #{{ $flightId }}</span>
                                                    <span>🌍 {{ $departure }} → {{ $arrival }}</span>
                                                    <span class="text-sm text-gray-500">
                                                        🕓 {{ $departureDate }} → {{ $arrivalDate }}
                                                    </span>
                                                    <svg class="fill-current h-6 w-6 transition-transform duration-200"
                                                        :class="openFlight ? 'rotate-180' : 'rotate-0'"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd">
                                                        </path>
                                                    </svg>
                                                </div>

                                                <div x-show="openFlight" x-collapse
                                                    class="bg-gray-50 p-2 mt-2 rounded-lg">
                                                    <div class="text-gray-800 font-semibold">👥 Passengers:</div>
                                                    <ul class="space-y-1">
                                                        @forelse ($users as $user)
                                                            @php
                                                                $userName = $user->name;
                                                                $userEmail = $user->email;
                                                                $bookingDate = date(
                                                                    'Y-m-d H:i',
                                                                    strtotime($user->pivot->created_at),
                                                                );
                                                            @endphp
                                                            <li
                                                                class="p-2 bg-white shadow-sm rounded-md flex flex-col md:flex-row text-xs md:text-sm gap-2 md:gap-0 justify-between">
                                                                <span>👤 {{ $userName }}</span>
                                                                <span>📧 {{ $userEmail }}</span>
                                                                <span class="text-sm text-gray-500">📅
                                                                    {{ $bookingDate }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="p-2 text-gray-500">No passengers on this flight.
                                                            </li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="p-2 text-gray-500">No flights available.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 bg-white text-gray-500 text-center rounded-lg">No airplanes available.</div>
                        @endforelse
                    @empty
                        <div class="p-4 bg-white text-gray-500 text-center rounded-lg">No data available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
