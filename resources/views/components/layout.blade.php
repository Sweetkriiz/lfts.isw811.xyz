@props([
    'title' => 'Laracasts'
])

<!DOCTYPE html>
<html lang="en" data-theme="sunset">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-primary">

    <x-nav />

    <main class="max-w-4xl mx-auto py-10">
        {{ $slot }}
    </main>

</body>

</html>