<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm  mt-2">All of your ideas in one place.</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 cursor-pointer h-32 w-full text-left">
                <p>What is your idea?</p>
            </x-card>

        </header>

        <div>
            <a href="/ideas" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}">All</a>

            @foreach (App\IdeaStatus::cases() as $status)
            <a
                href="/ideas?status={{ $status->value }}"
                class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">

                {{ $status->label() }} <span class="text-xs pl-3">{{ $statusCounts->get($status->value, 0) }}</span>
            </a>
            @endforeach
        </div>




        <div class="mt-10">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse ($ideas as $idea)
                <x-card href="{{ route('ideas.show', $idea) }}">
                    <h3 class="text-lg font-semibold">{{ $idea->title }}</h3>
                    <div class="mt-1">
                        <x-idea.status-label :status="$idea->status">
                            {{ $idea->status->label() }}
                        </x-idea.status-label>
                    </div>


                    <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                    <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>

                </x-card>
                @empty
                <x-card>
                    <p class="text-muted-foreground">No ideas yet.</p>
                </x-card>
                @endforelse
            </div>
        </div>

        <x-modal name="create-idea" title="New Idea">
            <form x-data="{ status: 'pending' }" method="POST" action="{{ route('idea.store') }}">
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter an idea for your title"
                        autofocus
                        required
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
                                    class="btn flex-1 h-10"
                                    :class={'btn-outlined': status !== @js($status->value)}
                                >
                                    {{ $status->label() }}
                                </button>
                            @endforeach

                            <input type="hidden" name="status" :value="status" class="input"/>
                        </div>

                        <x-field.error :name="'status'" />

                    </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe your idea...."
                    />
                </div>

                <div class="flex justify-end gap-x-5">
                    <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                    <button type="submit" class="btn">Create</button>

                </div>
            </form>
        </x-modal>

    </div>
</x-layout>
