<x-layout>
    <form method="POST" action="/ideas/{{ $idea->id }}">
        @csrf
        @method('PATCH')

        <div class="col-span-full">
            <label for="idea" class="block text-sm/6 font-medium text-white">
                Edit Your Idea
            </label>

            <div class="mt-2">
                <textarea
                    id="idea"
                    name="idea"
                    rows="3"
                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white"
                >{{ $idea->description }}</textarea>
            </div>
        </div>

    <div class="mt-6 flex items-center gap-x-2">
            <button
                type="submit"
                class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white"
            >
                Update
            </button>
        
            <button
            type="submit"
            form="delete-idea-form"
            class="rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white"
        >
            Delete
        </button>

    </div>
    </form>

<form id="delete-idea-form" method="post" action="/ideas/{{ $idea->id }}">
    @csrf
    @method('DELETE')
</form>
</x-layout>