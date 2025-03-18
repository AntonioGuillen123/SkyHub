<x-app-layout>
    <div class="py-12 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden flex flex-wrap justify-center">
                <div class="flex flex-col gap-4 w-2/3">
                    @foreach ($airplanes as $airplaneName => $airplane)
                        @foreach ($airplane as $maximumPlaces => $flights)
                            @php
                                $airplaneId = $flights[0]->airplane_id;
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
                                    <div class="p-2 text-gray-800 font-semibold">Flights:</div>
                                    <ul class="space-y-2">
                                        @foreach ($flights as $flight)
                                            @php
                                                $flightId = $flight->id;
                                                $departure = $flight->journey->destinationDeparture->name;
                                                $arrival = $flight->journey->destinationArrival->name;
                                                $departureDate = date(
                                                    'Y-m-d H:i',
                                                    strtotime($flight->flight_date),
                                                );
                                                $arrivalDate = date(
                                                    'Y-m-d H:i',
                                                    strtotime($flight->arrival_date),
                                                );
                                            @endphp
                                            <li class="p-2 bg-white shadow-sm rounded-md flex justify-between">
                                                <span>✈️Flight #{{ $flightId }}</span>
                                                <span>🌍{{ $departure }} → {{ $arrival }}</span>
                                                <span class="text-sm text-gray-500">
                                                    🕓 {{ $departureDate }} →
                                                    {{ $arrivalDate }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
