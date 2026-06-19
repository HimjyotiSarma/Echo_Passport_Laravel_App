@props([
    'type' => 'text',
    'name',
    'id',
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id }}"
    {{
        $attributes->class([
            'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder:text-slate-400',
            'focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition'
        ])
    }}
>
