<x-layout>

    <div class="card bg-neutral text-black p-6 mt-6">
        <div class="mt-6">
            {{ $idea->description }}

        </div>


        <div class="mt-6">
            <a href="/ideas/{{ $idea->id }}/edit"
                class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                Edit
            </a>
        </div>

       
    </div>

</x-layout>