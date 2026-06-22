
<x-layout>
    <form action="/login" method="POST">
        @csrf

        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4 mx-auto">
            <legend class="fieldset-legend">log in</legend>

            <label class="label" for="email">Email</label>
            <input type="email" class="input" name="email" placeholder="Your Email" required />

            <x-form-error name="email" />    

            <label class="label">Password</label>
            <input type="password" class="input" name="password" placeholder="Password" required />
            
            <x-form-error name="password" />

            <button class="btn btn-neutral mt-4">Register</button>
        </fieldset>
    </form>
</x-layout>