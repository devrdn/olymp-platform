@props(['legend' => ''])

<fieldset>
    @if ($legend)
        <h2 class="text-xl font-medium tracking-widest flex items-center font-mono mb-6">
            {{ $legend }}
            <span class="ml-2 w-full border-b border-gray-400 p-[2px] flex-grow relative"></span>
        </h2>
    @endif

    <div class="flex flex-col gap-6">
        {{ $slot }}
    </div>
</fieldset>
