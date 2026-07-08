@props(['status' => 'pending'])

@php

$classes ='inline-block rounded-full border px-2 py-1 text-xs font-medium bg-yellow-500/100 text-yellow-500 border-yellow-500/20';\

if($status === 'pending'){
$classes = 'inline-block rounded-full border px-2 py-1 text-xs font-medium bg-yellow-500/100 text-yellow-500 border-yellow-500/20';
}
if($status === 'in_progress'){
$classes = 'inline-block rounded-full border px-2 py-1 text-xs font-medium bg-blue-500/100 text-blue-500 border-blue-500/20';
}

if($status === 'completed'){
$classes = 'inline-block rounded-full border px-2 py-1 text-xs font-medium bg-green-500/100 text-green-500 border-green-500/20';
}
@endphp


<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}

</span>