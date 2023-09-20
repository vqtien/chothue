<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    @yield('seo')
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <div>
        <div id="header" class="bg-white">
            <div class="container-fluid mx-auto md:relative xs-s:fixed top-0 right-0 left-0 z-30">
                Header
            </div>
        </div>

        <div>
            <div class="container-fluid mx-auto lg:block xs-s:hidden">

            </div>
        </div>
        <main class="main-body">
            @yield('content')
        </main>

        <footer class="mb-20">
            <div id="FooterApp"></div>
        </footer>
    </div>
</body>

</html>
