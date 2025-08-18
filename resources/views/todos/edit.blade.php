@extends('layouts.app')

@section('title', '編輯 ToDo')

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">編輯 ToDo 項目</h1>
            <a href="{{ route('todos.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                返回列表
            </a>
        </div>

        <form method="POST" action="{{ route('todos.update', $todo) }}">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    標題 <span class="text-red-500">*</span>
                </label>
                <input id="title" 
                       type="text" 
                       name="title" 
                       value="{{ old('title', $todo->title) }}" 
                       required 
                       autofocus
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       placeholder="輸入 ToDo 項目標題">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    描述
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="輸入詳細描述（可選）">{{ old('description', $todo->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    狀態
                </label>
                <select id="status" 
                        name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                    <option value="pending" {{ old('status', $todo->status) === 'pending' ? 'selected' : '' }}>待處理</option>
                    <option value="in_progress" {{ old('status', $todo->status) === 'in_progress' ? 'selected' : '' }}>進行中</option>
                    <option value="completed" {{ old('status', $todo->status) === 'completed' ? 'selected' : '' }}>已完成</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('todos.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    取消
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    更新項目
                </button>
            </div>
        </form>
    </div>
</div>
@endsection