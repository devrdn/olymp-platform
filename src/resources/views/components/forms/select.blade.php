@props(['value', 'options' => [], 'id' => $attributes->get('id') ?? 'select-' . uniqid(), 'required' => false])

@php
    $defaultOption = collect($options)->firstWhere('selected', true);
    $defaultTitle = $defaultOption['title'] ?? 'Select...';
    $defaultValue = $defaultOption['value'] ?? '';
@endphp

<div {{ $attributes->merge(['class' => 'class="relative w-64 font-mono text-white']) }} x-data="{
    open: false,
    selectedValue: '{{ $defaultValue }}',
    selectedTitle: '{{ $defaultTitle }}',
    select(optionValue, optionTitle) {
        this.selectedValue = optionValue;
        this.selectedTitle = optionTitle;
        this.open = false;
        $nextTick(() => {
            const input = document.getElementById('{{ $id }}');
            if (input) {
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }
}">

    @if (isset($value))
        <label class='block mb-2 text-md font-semibold text-white'>
            {{ ucfirst($value) }}

            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <!-- Кнопка -->
        <button type="button" @click="open = !open"
            class="w-full flex justify-between items-center bg-gray-950 border border-gray-700 rounded-md px-4 py-2 text-left focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span x-text="selectedTitle"></span>
            <svg class="h-4 w-4 ml-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.292l3.71-4.06a.75.75 0 011.08 1.04l-4.25 4.656a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Выпадающий список -->
        <div x-show="open" @click.away="open = false" x-transition
            class="absolute mt-2 w-full max-h-60 overflow-y-auto bg-gray-950 border border-gray-700 rounded-md shadow-lg z-3">
            @foreach ($options as $option)
                <div @click="select('{{ $option['value'] }}', '{{ $option['title'] }}')"
                    class="flex items-center px-4 py-2 hover:bg-gray-700 cursor-pointer transition">
                    {{ $option['title'] }}
                </div>
            @endforeach
        </div>
    </div>

    <input type="hidden" id="{{ $id }}" name="{{ $id }}" :value="selectedValue">
</div>
