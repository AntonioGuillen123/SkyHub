<footer class="bg-[#36AEC2]">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 md:py-4">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex gap-3">
                <a href="{{ route('home') }}"
                    class="self-center text-2xl font-bold whitespace-nowrap text-[#FFDE59]">{{ config('app.name', 'Laravel') }}
                    |</a>
                <div class="flex gap-4 w-20">
                    <a href="https://github.com/AntonioGuillen123">
                        <img src="https://skillicons.dev/icons?i=github" />
                    </a>
                    <a href="www.linkedin.com/in/antonio-guillen-garcia">
                        <img src="https://skillicons.dev/icons?i=linkedin" />
                    </a>
                </div>
            </div>
            <ul class="flex flex-wrap items-center text-sm font-medium mt-4 md:mt-0 text-[#FFDE59]">
                <li>
                    <a href="{{ route('home') }}" class="hover:underline me-4 md:me-6">Home</a>
                </li>
                <li>
                    <a href="{{ route('indexFlight') }}" class="hover:underline me-4 md:me-6">Flights</a>
                </li>
                <li>
                    <a href="{{ url('api/documentation') }}" class="hover:underline me-4 md:me-6">API Docs</a>
                </li>
            </ul>
        </div>
    </div>
</footer>
