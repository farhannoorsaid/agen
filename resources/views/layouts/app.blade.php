<!DOCTYPE html>
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
        
        <style>
            /* Smooth transitions */
            * {
                transition: background-color 0.2s ease, border-color 0.2s ease;
            }
            
            /* Button improvements */
            button, a[role="button"], .btn {
                @apply transition-all duration-200 active:scale-95;
            }
            
            /* Input focus improvements */
            input, select, textarea {
                @apply transition-all duration-200;
            }
            
            /* Smooth scrolling */
            html {
                scroll-behavior: smooth;
            }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            ::-webkit-scrollbar-track {
                @apply bg-gray-100;
            }
            
            ::-webkit-scrollbar-thumb {
                @apply bg-gray-400 rounded-full;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                @apply bg-gray-600;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-8">
                @yield('content')
            </main>
        </div>
    </body>
</html>
