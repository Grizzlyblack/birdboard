<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Birdboard') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="theme-light bg-page">
    <div id="app">
        <nav class="bg-header section">
            <div class="container mx-auto">
                <div class="flex justify-between items-center py-2">
                    @include('layouts.logo')

                    <div>
                        <!-- Right Side Of Navbar -->
                        <div class="flex items-center">
                            <!-- Authentication Links -->
                            @guest
                                <a class="nav-link mr-4" href="{{ route('login') }}">{{ __('Login') }}</a>
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            @else
                                <theme-switcher></theme-switcher>

                                <dropdown align="right" width="100%">
                                    <template v-slot:trigger>
                                        <button class="flex items-center text-sm">
                                            <img src="{{gravatar_url(auth()->user()->email)}}" 
                                                class="rounded-full w-8 mr-3">

                                            {{auth()->user()->name}}
                                        </button>
                                    </template>

                                    {{-- <a href="#" class="dropdown-link" onclick="javascript: document.querySelector('#logout-form').submit()">Logout</a> --}}
                                    <form id="logout-form" method="POST" action="/logout">
                                        @csrf

                                        <button type="submit" class="dropdown-link w-full">Logout</button>
                                    </form>
                                </dropdown>

                                
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
