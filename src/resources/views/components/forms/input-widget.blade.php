@props([
    'disabled' => false,
    'title' => '',
    'required' => false,
    'name' => '',
])

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
        ]) }} />
</div>
