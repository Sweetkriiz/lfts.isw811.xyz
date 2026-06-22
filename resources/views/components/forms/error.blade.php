@props([

'name' => 'required'
])

@error('description')
<p class="text-red-500 text-xs italic">{{ $message }}</p>
@enderror