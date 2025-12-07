@props(['class' => 'w-5 h-5'])

<svg {{ $attributes->merge(['class' => $class]) }}
     fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M16 14a4 4 0 10-8 0m8 0a4 4 0 01-8 0m8 0
             c1.657 0 3 1.343 3 3v2H5v-2c0-1.657 1.343-3 3-3"/>
    <circle cx="12" cy="7" r="4" stroke-width="2"/>
</svg>
