@extends('layouts.app')

@section('title', 'ToDo List')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">我的 ToDo List</h1>
        <a href="{{ route('todos.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
            新增 ToDo
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Todos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">我的項目 ({{ $ownTodos->count() }})</h2>
            
            @if($ownTodos->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500 mb-4">還沒有任何 ToDo 項目</p>
                    <a href="{{ route('todos.create') }}" 
                       class="text-blue-600 hover:text-blue-500">
                        創建第一個項目
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($ownTodos as $todo)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 {{ $todo->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                                        {{ $todo->title }}
                                    </h3>
                                    @if($todo->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($todo->description, 100) }}</p>
                                    @endif
                                    <div class="flex items-center mt-2 space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($todo->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($todo->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($todo->status === 'pending') 待處理
                                            @elseif($todo->status === 'in_progress') 進行中
                                            @else 已完成 @endif
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $todo->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('todos.show', $todo) }}" 
                                       class="text-blue-600 hover:text-blue-500 text-sm">
                                        查看
                                    </a>
                                    <a href="{{ route('todos.edit', $todo) }}" 
                                       class="text-green-600 hover:text-green-500 text-sm">
                                        編輯
                                    </a>
                                    <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-500 text-sm"
                                                onclick="return confirm('確定要刪除這個項目嗎？')">
                                            刪除
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Collaborative Todos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">協作項目 ({{ $collaborativeTodos->count() }})</h2>
            
            @if($collaborativeTodos->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">還沒有參與任何協作項目</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($collaborativeTodos as $todo)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 {{ $todo->status === 'completed' ? 'line-through text-gray-500' : '' }}">
                                        {{ $todo->title }}
                                    </h3>
                                    @if($todo->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($todo->description, 100) }}</p>
                                    @endif
                                    <div class="flex items-center mt-2 space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($todo->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($todo->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($todo->status === 'pending') 待處理
                                            @elseif($todo->status === 'in_progress') 進行中
                                            @else 已完成 @endif
                                        </span>
                                        <span class="text-xs text-gray-500">由 {{ $todo->owner->name }} 創建</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('todos.show', $todo) }}" 
                                       class="text-blue-600 hover:text-blue-500 text-sm">
                                        查看
                                    </a>
                                    @can('update', $todo)
                                    <a href="{{ route('todos.edit', $todo) }}" 
                                       class="text-green-600 hover:text-green-500 text-sm">
                                        編輯
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection