<form
    {{ $attributes->merge([
        'class' => 'flex flex-col gap-6',
        'enctype' => 'multipart/form-data',
    ]) }}>
    {{ $slot }}
</form>
