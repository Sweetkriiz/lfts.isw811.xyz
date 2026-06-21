<x-layout>
    @if (count($tasks))
        <p>Yes, we have some tasks. How many? <?= count($tasks) ?> tasks, in fact! </p>
    @endif
</x-layout>