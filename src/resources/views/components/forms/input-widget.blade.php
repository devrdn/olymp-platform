@props([
    'disabled' => false,
    'title' => '',
    'required' => false,
])

@php
    $name = $attributes->get('name');
@endphp

<div>
    @if ($title)
        <x-forms.input-label :for="$name" required="true" class="mb-4">
            {{ $title }}
        </x-forms.input-label>
    @endif

    <x-forms.input
        {{ $attributes->merge([
            'disabled' => $disabled,
            'type' => 'text',
            'required' => $required,
            'name' => $name,
            'id' => $name,
        ]) }} />

    @error($name)
        <x-forms.error :message="$message" />
    @enderror
</div>
