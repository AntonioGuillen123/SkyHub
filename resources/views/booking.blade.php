<x-app-layout>
    <div class="py-12 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden flex flex-wrap justify-center">
                <div class="flex flex-col gap-4 w-4/5">
                    @forelse ($bookings as  $booking)
                        @php
                            $bookingId = $booking->id;
                            $departureDate = date('Y-m-d H:i', strtotime($booking->flight_date));
                            $arrivalDate = date('Y-m-d H:i', strtotime($booking->arrival_date));
                            $price = $booking->price;

                            $journey = $booking->journey;
                            $departure = $journey->destinationDeparture->name;
                            $arrival = $journey->destinationArrival->name;
                        @endphp

                        <div class="bg-white rounded-xl flex items-center text-xs md:text-sm justify-between p-2">
                            <div class="flex justify-center font-bold">✈️📅 Booking</div>
                            <div class="flex justify-center font-bold w-32">
                                <span>
                                    🌍 {{ $departure }} → {{ $arrival }}
                                </span>
                            </div>
                            <div class="flex justify-center font-bold">
                                <span class="text-gray-500">
                                    🕓 {{ $departureDate }} → {{ $arrivalDate }}
                                </span>
                            </div>
                            <div class="flex justify-center font-bold">
                                💸 {{ $price }} €
                            </div>
                            <div>
                                <form action="{{ route('cancelBooking') }}" method="POST">
                                    <input type="hidden" name="flight_id" value="{{ $bookingId }}">
                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                        class="bg-[#383E48] hover:bg-[#4c5057] text-red-500 p-1 px-2 rounded-xl">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 bg-white text-gray-500 text-center rounded-lg">No bookings available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
