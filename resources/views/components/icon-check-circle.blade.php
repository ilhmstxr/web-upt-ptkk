@props(['class' => 'w-5 h-5'])

<svg {{ $attributes->merge(['class' => $class]) }}
     fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 12l2 2 4-4"/>
    <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
</svg>
