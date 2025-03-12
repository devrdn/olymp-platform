@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border border-gray-700 bg-gray-900 text-gray-300 p-2 focus:border-primary focus:border-primary focus:ring-primary focus:ring-primary rounded-md shadow-sm w-full outline-none']) }} />
