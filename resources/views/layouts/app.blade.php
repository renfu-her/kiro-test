<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'ToDo List')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        @auth
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('todos.index') }}" class="text-xl font-semibold text-gray-900">
                            ToDo List
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('invitations.index') }}" class="text-gray-700 hover:text-gray-900 relative">
                            邀請
                            @php
                                $pendingCount = Auth::guard('member')->user()->receivedInvitations()->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                        <span class="text-gray-700">{{ Auth::guard('member')->user()->name }}</span>
                        <form method="POST" action="{{ route('member.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700">
                                登出
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        @endauth

        <!-- Page Content -->
        <main class="py-8">
            @yield('content')
        </main>
    </div>
</body>
</html>