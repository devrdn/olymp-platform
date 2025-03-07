@props(['disabled' => false])

<x-forms.input {{ $attributes->merge([
    'disabled' => $disabled,
    'type' => 'text',
]) }} />
