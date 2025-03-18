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
                            <div class="bg-white flex items-center justify-between p-2 rounded-xl">
                                {{-- <div class="flex justify-between w-full pl-4"> --}}
                                <div class="flex justify-center font-bold">{{ $airplaneId }}</div>
                                <div class="flex justify-center w-32">{{ $airplaneName }}</div>
                                <div class="flex justify-center">{{ $maximumPlaces }}</div>
                                <div class="cursor-pointer">
                                    <svg class="fill-current h-8 w-8" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
