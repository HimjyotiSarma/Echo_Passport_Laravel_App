{{-- <div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div> --}}

@props([
    'type' => 'button'
])

<button
    type="{{ $type }}"
    {{
        $attributes->merge([
            'class' =>
                'w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white
                 hover:bg-blue-700
                 active:bg-blue-800
                 transition'
        ])
    }}
>
    {{ $slot }}
</button>
