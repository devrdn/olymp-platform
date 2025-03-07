<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => '
        bg-primary text-white rounded-lg font-semibold cursor-pointer
        hover:bg-primary-dark
        py-2 px-4
    ',
    ]) }}>
    {{ $slot }}
</button>
