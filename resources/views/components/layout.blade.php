@props([
    'title' => 'Laracasts'
])

<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />

</head>

<body class="min-h-screen bg-base-200 text-base-content">

    <x-nav />

    <main class="max-w-4xl mx-auto py-10">
        {{ $slot }}
    </main>

</body>
</html>