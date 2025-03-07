@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block mb-4 text-md font-semibold text-white']) }}>
    {{ $value ?? $slot }}

    @if ($required)
        <span class="text-red-500">*</span>
    @endif
</label>
