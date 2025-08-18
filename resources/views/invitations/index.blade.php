@extends('layouts.app')

@section('title', '邀請管理')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">邀請管理</h1>
        <a href="{{ route('todos.index') }}" 
           class="text-gray-600 hover:text-gray-800">
            返回 ToDo 列表
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">待處理的邀請 ({{ $pendingInvitations->count() }})</h2>
        
        @if($pendingInvitations->isEmpty())
            <div class="text-center py-8">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3" />
                    </svg>
                </div>
                <p class="text-gray-500">目前沒有待處理的邀請</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingInvitations as $invitation)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    {{ $invitation->todo->title }}
                                </h3>
                                
                                @if($invitation->todo->description)
                                    <p class="text-sm text-gray-600 mb-3">
                                        {{ Str::limit($invitation->todo->description, 100) }}
                                    </p>
                                @endif
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>
                                        <strong>邀請者：</strong>{{ $invitation->inviter->name }}
                                    </span>
                                    <span>
                                        <strong>邀請時間：</strong>{{ $invitation->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3 ml-4">
                                <!-- Accept Button -->
                                <form method="POST" action="{{ route('invitations.accept', $invitation) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition duration-200">
                                        接受
                                    </button>
                                </form>
                                
                                <!-- Reject Button -->
                                <form method="POST" action="{{ route('invitations.reject', $invitation) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition duration-200"
                                            onclick="return confirm('確定要拒絕這個邀請嗎？')">
                                        拒絕
                                    </button>
                                </form>
                                
                                <!-- View Todo Button -->
                                <a href="{{ route('todos.show', $invitation->todo) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition duration-200">
                                    查看項目
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection