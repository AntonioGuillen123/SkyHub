@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#FFDE59] text-sm font-medium leading-5 text-[#FFDE59] focus:outline-none focus:border-[#c5a938] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[#ffe57b] hover:text-[#FFDE59] hover:border-[#FFDE59] focus:outline-none focus:text-[#FFDE59] focus:border-[#FFDE59] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
