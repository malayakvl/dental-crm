<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
{{--            @include('layouts.navigation')--}}

            <!-- Page Content -->
            <main class="container-fluid g-0">
                <div class="float-start left-sidebar">
                    <div class="logo">
                        <img src="/images/logo.svg">
                        <span class="brand">Dental CRM</span>
                    </div>
                    <div class="toggle-btn float-end"><a href="javascript:;"><i class="fas fa-angle-double-left"></i></a></div>
                    <div class="clearfix"></div>
                    <form class="form-search">
                        <div class="input-group mb-3">
                            <input class="form-control w-75" list="datalistOptions" id="exampleDataList" placeholder="Type to search...">
                            <a href="javascript:;" class="btn float-end"><i class="fas fa-search"></i></a>
                        </div>
                    </form>
                    <ul class="left-nav-bar">
                        <li><a href="javascript:;"><i class="fas fa-clinic-medical"></i> Добавить клинику</a></li>
                    </ul>
                </div>
                <div class="float-start">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
