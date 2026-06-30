@props(['title', 'description'])

<section class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold">{{ $title }}</h1>
    <p class="mt-2 text-muted-foreground">{{ $description }}</p>

    {{ $slot }}
</section>