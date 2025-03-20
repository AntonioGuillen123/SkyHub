@section('title')
    {{ config('app.name', 'Laravel') }} | Flights
@endsection

<x-app-layout>
    <div class="py-12 h-full">
        <div class="flex flex-col gap-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-filters></x-filters>

            <div class="overflow-hidden flex flex-wrap justify-center gap-8">
                @foreach ($flights as $flight)
                    @php
                        $flightId = $flight->id;
                        $price = $flight->price;
                        $remainingPlaces = $flight->remaining_places;
                        $flightDateTime = date('Y-m-d H:i', strtotime($flight->flight_date));
                        $flightDate = explode(' ', $flightDateTime)[0];
                        $flightTime = explode(' ', $flightDateTime)[1];
                        $arrivalDateTime = date('Y-m-d H:i', strtotime($flight->arrival_date));
                        $arrivalTime = explode(' ', $arrivalDateTime)[1];

                        $airplane = $flight->airplane;
                        $totalPlaces = $airplane->maximum_places;

                        $journey = $flight->journey;
                        $departure = $journey->destinationDeparture->name;
                        $arrival = $journey->destinationArrival->name;

                        $isActive = $flight->state;
                        $isBooked = $flight->booked;

                        $statusClass = $isActive ? 'border-emerald-500' : 'border-red-500';
                        $bookedClass = $isActive && !$isBooked ? 'text-emerald-500' : 'text-red-500';
                        $bookedBgHoverClass = 'bg-[#4c5057]';
                        $bookingRoute = $isBooked ? 'cancelBooking' : 'makeBooking';
                        $bookingText = $isBooked ? 'Cancel' : 'Reserve';
                    @endphp

                    <div class="flex flex-col gap-2 bg-white p-2 border-2 {{ $statusClass }} rounded-lg w-64">
                        <div>
                            <div class="flex justify-center font-semibold">📅 {{ $flightDate }}</div>
                            <div class="flex justify-around">
                                <div>
                                    <div class="font-semibold">🏙️ {{ $departure }}</div>
                                    <div class="flex justify-center text-gray-700">🕛 {{ $flightTime }}</div>
                                </div>
                                <div class="text-3xl">→</div>
                                <div>
                                    <div class="font-semibold">🌆 {{ $arrival }}</div>
                                    <div class="flex justify-center text-gray-700">🕡 {{ $arrivalTime }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col text-gray-700">
                            <div class="flex justify-between">
                                <div class="font-semibold">💰 Price:</div>
                                <div>{{ $price }} €</div>
                            </div>
                            <div class="flex justify-between">
                                <div class="font-semibold">💺 Places:</div>
                                <div>{{ $remainingPlaces }} / {{ $totalPlaces }}</div>
                            </div>
                        </div>
                        @if (!$isActive && !$isBooked)
                        @else
                            <div class="flex justify-center">
                                <form action="{{ route($bookingRoute) }}" method="POST">
                                    <input type="hidden" name="flight_id" value="{{ $flightId }}">
                                    @csrf
                                    @if ($isBooked)
                                        @method('DELETE')
                                    @endif
                                    <button type="submit"
                                        class="bg-[#383E48] hover:{{ $bookedBgHoverClass }} {{ $bookedClass }} p-1 px-2 rounded-xl">
                                        {{ $bookingText }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
