<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard Dosen')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    @include('partials.dosen-sidebar')
     <main class="flex-1 overflow-y-auto">

    <div class="flex-1 flex flex-col">

       {{-- Navbar --}}
             <header class="sticky top-0 z-50 bg-white border-b shadow-sm">
           @include('partials.navbar')
             </header>
 

        <main class="p-6">

            @yield('content')

        </main>

    </div>

</div>

<script src="{{ asset('js/realtime.js') }}"></script>

</body>
</html>