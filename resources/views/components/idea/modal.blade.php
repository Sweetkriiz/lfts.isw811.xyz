@props(['idea' => new App\Models\Idea()])

<x-modal
    name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}"
>
    <form
        x-data="{
            status: @js(old('status', $idea->status?->value ?? App\IdeaStatus::Pending->value)),
            newLink: '',
            links: @js(old('links', $idea->links ?? [])),
            newStep: '',
            steps: @js(old('steps', $idea->steps?->pluck('description')->all() ?? []))
        }"
        method="POST"
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        @if ($idea->exists)
            @method('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter a title for your idea"
                autofocus
                required
                :value="old('title', $idea->title ?? '')"
            />

            <div class="space-y-2">
                <label for="status" class="label">
                    Status
                </label>

                <div class="flex gap-x-3">
                    @foreach (App\IdeaStatus::cases() as $status)
                        <button
                            type="button"
                            @click="status = @js($status->value)"
                            data-test="button-status-{{ $status->value }}"
                            class="btn h-10 flex-1"
                            :class="{ 'btn-outlined': status !== @js($status->value) }"
                        >
                            {{ $status->label() }}
                        </button>
                    @endforeach

                    <input
                        type="hidden"
                        name="status"
                        :value="status"
                    >
                </div>

                <x-form.error name="status" />
            </div>

            <x-form.field
                label="Description"
                name="description"
                type="textarea"
                placeholder="Describe your idea..."
                :value="old('description', $idea->description ?? '')"
            />

            <div class="space-y-2">
                <label for="image" class="label">
                    Featured Image
                </label>

                @if ($idea->image_path)
                    <div class ="space-y-2">
                        <img src="{{ asset('storage/' . $idea->image_path) }}" all="{{ $idea->title }}"
                            class="w-full h-48 object-cover rounded-lg">

                            <button type="button" class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>

                    </div>
                @endif

                <input
                    type="file"
                    name="image"
                    accept="image/*"
                >

                <x-form.error name="image" />
            </div>

            <div>
                <fieldset class="space-y-2">
                    <legend class="label">
                        Actionable Steps
                    </legend>

                    <template
                        x-for="(step, index) in steps"
                        :key="`${step}-${index}`"
                    >
                        <div class="flex items-center gap-x-2">
                            <input
                                type="text"
                                name="steps[]"
                                x-model="steps[index]"
                                class="input flex-1"
                            >

                            <button
                                type="button"
                                aria-label="Remove step"
                                @click="steps.splice(index, 1)"
                                class="form-muted-icon"
                            >
                                <x-icons.close />
                            </button>
                        </div>
                    </template>

                    <div class="flex items-center gap-x-2">
                        <input
                            x-model="newStep"
                            type="text"
                            id="new-step"
                            data-test="new-step"
                            placeholder="What needs to be done?"
                            class="input flex-1"
                            spellcheck="false"
                        >

                        <button
                            type="button"
                            @click="
                                steps.push(newStep.trim());
                                newStep = '';
                            "
                            data-test="submit-new-step-button"
                            :disabled="newStep.trim().length === 0"
                            aria-label="Add a new step"
                            class="form-muted-icon"
                        >
                            <x-icons.close class="rotate-45" />
                        </button>
                    </div>
                </fieldset>
            </div>

            <div>
                <fieldset class="space-y-3">
                    <legend class="label">
                        Links
                    </legend>

                    <template
                        x-for="(link, index) in links"
                        :key="`${link}-${index}`"
                    >
                        <div class="flex items-center gap-x-2">
                            <input
                                type="url"
                                name="links[]"
                                x-model="links[index]"
                                class="input flex-1"
                            >

                            <button
                                type="button"
                                aria-label="Remove link"
                                @click="links.splice(index, 1)"
                                class="form-muted-icon"
                            >
                                <x-icons.close />
                            </button>
                        </div>
                    </template>

                    <div class="flex items-center gap-x-2">
                        <input
                            x-model="newLink"
                            type="url"
                            id="new-link"
                            data-test="new-link"
                            placeholder="https://example.com"
                            autocomplete="url"
                            class="input flex-1"
                            spellcheck="false"
                        >

                        <button
                            type="button"
                            @click="
                                links.push(newLink.trim());
                                newLink = '';
                            "
                            data-test="submit-new-link-button"
                            :disabled="newLink.trim().length === 0"
                            aria-label="Add a new link"
                            class="form-muted-icon"
                        >
                            <x-icons.close class="rotate-45" />
                        </button>
                    </div>
                </fieldset>
            </div>

            <div class="flex justify-end gap-x-5">
                <button
                    type="button"
                    @click="$dispatch('close-modal')"
                >
                    Cancel
                </button>

                <button type="submit" class="btn">
                    {{ $idea->exists ? 'Update' : 'Create' }}
                </button>
            </div>
        </div>
    </form>

   @if ($idea->image_path)
    <form method="POST" action="{{ route('ideas.image.destroy', $idea) }}" id="delete-image-form">
    @csrf
    @method('DELETE')
</form>
@endif
</x-modal>
