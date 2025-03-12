@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#FFDE59] text-start text-base font-medium text-[#FFDE59] bg-[#383E48] focus:outline-none focus:text-[#c5a938] focus:bg-[#4c5057] focus:border-[#c5a938] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[#ffe57b] hover:text-[#FFDE59] bg-[#383E48] hover:bg-[#4c5057] hover:border-[#c5a938] focus:outline-none focus:text-[#c5a938] focus:bg-[#4c5057] focus:border-[#c5a938] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
