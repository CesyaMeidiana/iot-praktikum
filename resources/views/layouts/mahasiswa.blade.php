<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title','Dashboard Mahasiswa')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>

</head>

<body class="bg-slate-100 overflow-hidden">

<div class="h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-72 h-screen fixed left-0 top-0 z-50">

        @include('partials.mahasiswa-sidebar')

    </aside>

    {{-- Content --}}
    <div class="flex-1 ml-72 flex flex-col h-screen">

        {{-- Navbar --}}
        <header class="sticky top-0 z-40 bg-white">

            @include('partials.mahasiswa-navbar')

        </header>

        {{-- Main Content --}}
        <main
            class="
                flex-1
                overflow-y-auto
                bg-slate-100
                px-8
                py-6
            ">

            @yield('content')

        </main>

    </div>

</div>
@vite(['resources/css/app.css', 'resources/js/app.js'])

<script src="{{ asset('js/realtime.js') }}"></script>
</body>

</html>