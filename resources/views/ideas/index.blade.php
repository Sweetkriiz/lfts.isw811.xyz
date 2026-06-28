<x-layout>

    @if ($ideas->count())

    <div class="mt-6 text-black">
        <h2 class="font-bold">Your ideas </h2>

        <ul class="mt-6 grid grid-cols-2 gap-x-6 gap-y-4">
            @foreach($ideas as $idea)

            <x-idea-card href="/ideas/{{ $idea->id }}">
                {{ $idea->description }}
            </x-idea-card>

            @endforeach
        </ul>
    </div>
    @else
    <p>You have no ideas. </p>
    @endif

   <p class="mt-6"><a href="/ideas/create" class="underline">Create one now</a></p>
</x-layout>