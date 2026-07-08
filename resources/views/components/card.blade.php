@props(['is' => null])

@php
    $tag = $is ?? ($attributes->has('href') ? 'a' : 'div');

    $cardAttributes = $attributes->merge([
        'class' => 'border border-border rounded-lg bg-card p-4 md:text-sm block',
    ]);

    if ($tag === 'button' && ! $attributes->has('type')) {
        $cardAttributes = $cardAttributes->merge(['type' => 'button']);
    }
@endphp

<{{ $tag }} {{ $cardAttributes }}>
    {{ $slot }}
</{{ $tag }}>
