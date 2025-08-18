@extends('layouts.app')

@section('title', $todo->title)

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ToDo 詳情</h1>
            <a href="{{ route('todos.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                返回列表
            </a>
        </div>

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 {{ $todo->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                    {{ $todo->title }}
                </h2>
            </div>

            <!-- Status -->
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($todo->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($todo->status === 'in_progress') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800 @endif">
                    @if($todo->status === 'pending') 待處理
                    @elseif($todo->status === 'in_progress') 進行中
                    @else 已完成 @endif
                </span>
            </div>

            <!-- Description -->
            @if($todo->description)
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">描述</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $todo->description }}</p>
                </div>
            </div>
            @endif

            <!-- Meta Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">項目資訊</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">創建者</p>
                        <p class="font-medium">{{ $todo->owner->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">創建時間</p>
                        <p class="font-medium">{{ $todo->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">最後更新</p>
                        <p class="font-medium">{{ $todo->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @if($todo->collaborators->isNotEmpty())
                    <div>
                        <p class="text-sm text-gray-600">協作者</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($todo->collaborators as $collaborator)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $collaborator->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Collaboration Section -->
            @can('update', $todo)
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">協作管理</h3>
                
                <!-- Invite Collaborator Form -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">邀請協作者</h4>
                    <form method="POST" action="{{ route('invitations.store', $todo) }}" class="flex items-end space-x-3">
                        @csrf
                        <div class="flex-1">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                電子郵件
                            </label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="輸入要邀請的用戶電子郵件">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            發送邀請
                        </button>
                    </form>
                </div>

                <!-- Current Collaborators -->
                @if($todo->collaborators->isNotEmpty())
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">目前協作者</h4>
                    <div class="space-y-2">
                        @foreach($todo->collaborators as $collaborator)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                        {{ strtoupper(substr($collaborator->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $collaborator->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $collaborator->email }}</p>
                                    </div>
                                </div>
                                
                                @can('delete', $todo)
                                <form method="POST" action="{{ route('collaborators.remove', $todo) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="user_id" value="{{ $collaborator->id }}">
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 text-sm"
                                            onclick="return confirm('確定要移除此協作者嗎？')">
                                        移除
                                    </button>
                                </form>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Pending Invitations -->
                @php
                    $sentInvitations = $todo->invitations()->where('status', 'pending')->with('invitee')->get();
                @endphp
                
                @if($sentInvitations->isNotEmpty())
                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3">待處理邀請</h4>
                    <div class="space-y-2">
                        @foreach($sentInvitations as $invitation)
                            <div class="flex items-center justify-between bg-yellow-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                        {{ strtoupper(substr($invitation->invitee->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $invitation->invitee->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $invitation->invitee->email }} • {{ $invitation->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                @can('delete', $todo)
                                <form method="POST" action="{{ route('invitations.cancel', $invitation) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 text-sm"
                                            onclick="return confirm('確定要取消此邀請嗎？')">
                                        取消邀請
                                    </button>
                                </form>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endcan

            <!-- Actions -->
            @can('update', $todo)
            <div class="border-t pt-6">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('todos.edit', $todo) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        編輯項目
                    </a>
                    
                    @can('delete', $todo)
                    <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200"
                                onclick="return confirm('確定要刪除這個項目嗎？')">
                            刪除項目
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection