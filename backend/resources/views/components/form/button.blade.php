{{-- <div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div> --}}

@props([
    'type' => 'button',
    'variant' => 'primary',
])

@php
    $variants = [
        'primary' =>
            'bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800',

        'secondary' =>
            'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 active:bg-slate-200',

        'danger' =>
            'bg-red-600 text-white hover:bg-red-700 active:bg-red-800',
    ];
@endphp

<button
    type="{{ $type }}"
    {{
        $attributes->class([
            'w-full rounded-xl px-4 py-3 text-sm font-semibold transition',
            $variants[$variant] ?? $variants['primary'],
        ])
    }}
>
    {{ $slot }}
</button>
