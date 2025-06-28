{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}

@props(['title'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '-' }} Sakinah Collection</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-color: #d4af37;
            --primary-hover: #b8972f;
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        .hover\:bg-primary:hover {
            background-color: var(--primary-hover);
        }

        .text-primary {
            color: var(--primary-color);
        }

        .border-primary {
            border-color: var(--primary-color);
        }
    </style>
</head>

<body class="bg-gray-100 font-sans flex flex-col min-h-screen">
    <div class="flex flex-1 h-screen">
        <!-- Sidebar -->
       @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col w-full md:ml-64">

            <!-- Content -->
            {{ $slot }}

            <!-- Footer -->
            <footer class="bg-white shadow-inner p-4 text-center text-sm text-gray-600">
                © {{ date('Y') }} Sakinnah Collection. All rights reserved.
            </footer>
        </div>
    </div>

    <script>
        const openSidebarBtn = document.querySelector('#open-sidebar');
        const closeSidebarBtn = document.querySelector('#close-sidebar');
        const sidebar = document.querySelector('#sidebar');
        const profileBtn = document.querySelector('#profile-btn');
        const profileDropdown = document.querySelector('#profile-dropdown');

        // Sidebar toggle
        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
        });

        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !openSidebarBtn.contains(e.target) && !sidebar.classList.contains(
                    '-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Profile dropdown toggle
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
