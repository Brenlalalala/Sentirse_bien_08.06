@props(['active' => false, 'href' => '#'])

@php
$baseClasses = 'flex items-center gap-3 px-4 py-3 rounded-md text-gray-600 text-sm font-medium transition-colors duration-200';

$activeClasses = $active
    ? 'bg-pink-600 text-pink-500 shadow-lg'
    : 'hover:bg-pink-100 hover:text-pink-700';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $activeClasses"]) }}>
    {{ $slot }}
</a>
