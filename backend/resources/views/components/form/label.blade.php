{{-- <div>
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
</div> --}}
@props([
    'for'
])

<label
    for="{{ $for }}"
    class="mb-2 block text-sm font-medium text-slate-700"
>
    {{ $slot }}
</label>
