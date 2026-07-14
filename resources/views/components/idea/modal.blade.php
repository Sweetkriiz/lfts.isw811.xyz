@props(['idea' => new App\Models\Idea()])

<x-modal
    name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}"
>
    <form
        x-data="{
            status: @js(
                old(
                    'status',
                    $idea->status?->value ?? App\IdeaStatus::PENDING->value
                )
            ),

            newLink: '',

            links: @js(
                old('links', $idea->links ?? [])
            ),

            newStep: '',

            steps: @js(
                old(
                    'steps',
                    $idea->exists
                        ? $idea->steps
                            ->map
                            ->only(['id', 'description', 'completed'])
                            ->values()
                        : []
                )
            )
        }"
        method="POST"
        action="{{ $idea->exists
            ? route('ideas.update', $idea)
            : route('ideas.store') }}"
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
                :value="$idea->title"
            />

            <div class="space-y-2">
                <label class="label">
                    Status
                </label>

                <div class="flex gap-x-3">
                    @foreach (App\IdeaStatus::cases() as $ideaStatus)
                        <button
                            type="button"
                            @click="status = @js($ideaStatus->value)"
                            data-test="button-status-{{ $ideaStatus->value }}"
                            class="btn h-10 flex-1"
                            :class="{
                                'btn-outlined':
                                    status !== @js($ideaStatus->value)
                            }"
                        >
                            {{ $ideaStatus->label() }}
                        </button>
                    @endforeach
                </div>

                <input
                    type="hidden"
                    name="status"
                    :value="status"
                >

                <x-form.error name="status" />
            </div>

            <x-form.field
                label="Description"
                name="description"
                type="textarea"
                placeholder="Describe your idea..."
                :value="$idea->description"
            />

            <div class="space-y-2">
                <label for="image" class="label">
                    Featured Image
                </label>

                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img
                            src="{{ asset('storage/' . $idea->image_path) }}"
                            alt="{{ $idea->title }}"
                            class="h-48 w-full rounded-lg object-cover"
                        >

                        <button
                            type="submit"
                            class="btn btn-outlined h-10 w-full"
                            form="delete-image-form"
                        >
                            Remove Image
                        </button>
                    </div>
                @endif

                <input
                    type="file"
                    name="image"
                    id="image"
                    accept="image/*"
                >

                <x-form.error name="image" />
            </div>

            <fieldset class="space-y-2">
                <legend class="label">
                    Actionable Steps
                </legend>

                <template
                    x-for="(step, index) in steps"
                    :key="step.id ?? `new-${index}`"
                >
                    <div class="flex items-center gap-x-2">
                        <input
                            type="text"
                            :name="`steps[${index}][description]`"
                            x-model="step.description"
                            class="input"
                            readonly
                        >

                        <input
                            type="hidden"
                            :name="`steps[${index}][completed]`"
                            :value="step.completed ? '1' : '0'"
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
                        @keydown.enter.prevent="
                            if (newStep.trim().length > 0) {
                                steps.push({
                                    description: newStep.trim(),
                                    completed: false
                                });

                                newStep = '';
                            }
                        "
                    >

                    <button
                        type="button"
                        @click="
                            steps.push({
                                description: newStep.trim(),
                                completed: false
                            });

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

                <x-form.error name="steps" />
            </fieldset>

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
                            :name="`links[${index}]`"
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
                        @keydown.enter.prevent="
                            if (newLink.trim().length > 0) {
                                links.push(newLink.trim());
                                newLink = '';
                            }
                        "
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

                <x-form.error name="links" />
            </fieldset>

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
        <form
            method="POST"
            action="{{ route('ideas.image.destroy', $idea) }}"
            id="delete-image-form"
        >
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>
