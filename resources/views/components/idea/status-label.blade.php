@props(['status' => null])

@php
    $status = $status instanceof App\IdeaStatus
        ? $status
        : App\IdeaStatus::tryFrom((string) $status);

    $classes = match ($status) {
        App\IdeaStatus::IN_PROGRESS => 'inline-block rounded-full border px-2 py-1 text-xs font-medium bg-blue-500/10 text-blue-400 border-blue-500/20',
        App\IdeaStatus::COMPLETED => 'inline-block rounded-full border px-2 py-1 text-xs font-medium bg-green-500/10 text-green-400 border-green-500/20',
        default => 'inline-block rounded-full border px-2 py-1 text-xs font-medium bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
