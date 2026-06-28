<div class="navbar bg-base-200 shadow-sm">
    <div class="navbar-start">
        <a href="/ideas" class="btn btn-ghost text-xl">IDEA</a>
    </div>

    <div class="navbar-center">
        <ul class="menu menu-horizontal px-1">
            <li><a href="/ideas">HOME</a></li>
            <li><a href="/ideas/create">NEW IDEA</a></li>
            <li><a href="/admin">ADMIN</a></li>
            @can('view-admin')
                <li><a> href="/admin">Admin</a></li>
            @endcan

        </ul>
    </div>

    <div class="navbar-end space-x-2">
        @guest
        <a class="btn btn-primary" href="/register">Register</a>
        <a class="btn btn-ghost" href="/login">Log in</a>
        @endguest

        @auth
        <form method="POST" action="/logout">
            @csrf
            @method('DELETE')

            <button class="btn btn-ghost" type="submit">
                Log out
            </button>
        </form>
        @endauth
    </div>
</div>