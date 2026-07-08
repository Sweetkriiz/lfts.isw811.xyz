@props(['name', 'title' => null])

<div
    x-data="{ show: false }"
    x-show="show"
    @open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    @keydown.escape.window="show = false"
    x-transition.opacity.duration.300ms
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 backdrop-blur-xs"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-{{ $name }}-title"
    :aria-hidden="!show"
>
    <x-card
        @click.outside="show = false"
        class="w-full max-w-lg shadow-xl"
    >
        @if ($title)
            <h2 id="modal-{{ $name }}-title" class="text-lg font-semibold">{{ $title }}</h2>
        @endif

        <div class="mt-4">
            {{ $slot }}
        </div>
    </x-card>
</div>
