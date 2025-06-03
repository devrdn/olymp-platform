@extends('layouts::contest')

@php
    $languages = [
        ['value' => 'html', 'title' => 'html', 'selected' => true],
        ['value' => 'php', 'title' => 'PHP'],
        ['value' => 'cpp', 'title' => 'C++'],
        ['value' => 'python', 'title' => 'Python'],
    ];
@endphp

@section('scripts')
    @vite('resources/js/editor.js')
@endsection

@section('content')
    <div class="flex">
        <!-- Left part -->
        <div class="flex flex-col w-1/2 p-6 overflow-y-auto">
            <div class="mb-6 min-h-[50vh] h-auto flex flex-col gap-6">
                <div id="task-description">
                    <h2 class="text-3xl font-semibold mb-4">
                        {{ $task->title }}
                    </h2>
                    <p class="text-gray-300">
                        {!! $task->description !!}
                    </p>
                </div>
                <div id="task-memory-limit">
                    <h2 class="text-xl font-medium font-mono mb-4">
                        {{ __('Memory Limit') }}
                    </h2>
                    <p class="text-gray-300">
                        {{ $task->memory_limit }} MB
                    </p>
                </div>
                <div id="task-memory-limit">
                    <h2 class="text-xl font-medium font-mono mb-4">
                        {{ __('Time limit') }}
                    </h2>
                    <p class="text-gray-300">
                        {{ $task->time_limit }} s
                    </p>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <h2 class="text-2xl font-bold font-mono mb-4">Tests</h2>
                <p class="text-gray-600">No tests yet...</p>
            </div>
        </div>

        <!-- Right part -->
        <form id="solution-form" class="flex flex-col w-1/2 h-[70vh] p-6">
            <h2 class="text-3xl font-semibold mb-4">{{ __('Solution') }}</h2>
            <x-forms.select id="language" class="mb-3" :options="$languages" required />
            <div class="relative w-full">
                <button id="fullscreen-btn" type="button"
                    class="fullscreen-icon group absolute top-2 right-2 z-10 bg-gray-900 hover:bg-gray-800 p-2 rounded transition"
                    aria-label="Полноэкранный режим">
                    <!-- SVG ВХОД (развернуть) -->
                    <svg id="fullscreen-enter" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" fill="none"
                        viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 3H5a2 2 0 0 0-2 2v3m0 8v3a2 2 0 0 0 2 2h3m8-18h3a2 2 0 0 1 2 2v3m0 8v3a2 2 0 0 1-2 2h-3" />
                    </svg>
                    <!-- SVG ВЫХОД (крестик) -->
                    <svg id="fullscreen-exit" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="white" stroke-width="2" style="z-index: 10001;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="editor" class="flex-1 border border-gray-900 rounded-md"></div>
            <p class="text-base mt-3">or upload file</p>
            <label for="fileInput" class="block my-3 cursor-pointer">
                <span class="sr-only cursor-pointer">Выберите файл</span>
                <input type="file" id="fileInput"
                    class="block cursor-pointer w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700
                  hover:file:bg-blue-100
                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                  transition duration-150 ease-in-out
                " />
            </label>
            <button id="uploadSolution"
                class="mt-4 inline-block bg-primary px-3 py-2 rounded-md hover:bg-primary-dark cursor-pointer">
                Upload Solution
            </button>
        </form>
    </div>
@endsection
