{{-- <div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div> --}}

@props([
    'method' => 'POST',
    'action' => '#',
])

<form
    method="{{ $method }}"
    action="{{ $action }}"
    {{
        $attributes->merge([
            'class' =>
                'space-y-5'
        ])
    }}
>
    {{ $slot }}
</form>
