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
        <x-forms.input-label :for="$name" :required="$required" class="mb-4">
            {{ $title }}
        </x-forms.input-label>
    @endif

    <x-forms.textarea
        {{ $attributes->merge([
            'disabled' => $disabled,
            'type' => 'text',
            'required' => $required,
            'id' => $name,
        ]) }} />

    @error($name)
        <x-forms.error :message="$message" />
    @enderror
</div>
