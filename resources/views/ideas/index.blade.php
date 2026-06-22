<x-layout>

    @if ($ideas->count())

    <div class="mt-6 text-white">
        <h2 class="font-bold">Your ideas </h2>

        <ul class="mt-6">
            @foreach($ideas as $idea)

            <a href="/ideas/{{ $idea->id }}" class="text-sm">
                {{ $idea->description }}
            </a>


            @endforeach
        </ul>
    </div>
    @else
    <p>
        You have no ideas.
        <a href="/ideas/create" class="underline">Create one now</a>
    </p>
    @endif
</x-layout>