<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Activity System</a>
        <div>
            @auth
                <span class="text-light me-2">{{ auth()->user()->name }}</span>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-light">Logout</a>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="btn btn-sm btn-light">Login</a>
            @endguest
        </div>
    </div>
</nav>
