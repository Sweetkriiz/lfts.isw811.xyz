<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm  mt-2">All of your ideas in one place.</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                class="mt-10 cursor-pointer h-32 w-full">
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

             <x-modal name="create-idea" title="Create Idea">
                <p>Slot content here.</p>
             </x-modal>
                 
        </div>
    </div>
</x-layout>
