@php use Illuminate\Support\Facades\Auth; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <div class="p-4 border">
        <div>
            <a href="{{ route('products.index') }}" class="font-mono underline">Catalog</a>
            <a href="{{ route('cart.show') }}" class="font-mono underline ml-4">Cart</a>
            <a href="{{ route('discounts.index') }}" class="font-mono underline ml-4">Discounts</a>
        <div class="text-right">
            @if(!Auth::check())
                <a href="{{ route('login') }}" class="font-mono underline">Login</a>
                <span class="mx-2">|</span>
                <a href="{{ route('register') }}" class="font-mono underline">Register</a>
            @else
                <span>Hello
                    <a href="{{ route('profile.edit') }}">
                        <span class="mx-2 font-bold underline">{{ Auth::user()->name }}</span>
                        <span class="mx-2">|</span>
                    </a>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="font-mono underline">Logout</button>
                </form>
            @endif
        </div>
        </div>
    </div>
    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
</div>
</body>
</html>
