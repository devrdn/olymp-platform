@unless ($breadcrumbs->isEmpty())
    <nav>
        <ol class="rounded flex flex-wrap text-sm text-gray-800 gap-2">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    <li>
                        <a href="{{ $breadcrumb->url }}"
                            class="gap-2 font-mono text-sm font-medium tracking-widest text-blue-500 uppercase focus:text-blue-900 focus:underline">
                            {{ $breadcrumb->title }}
                        </a>
                    </li>
                @else
                    <li class="gap-2 font-mono text-sm font-medium tracking-widest text-gray-400 uppercase">
                        {{ $breadcrumb->title }}
                    </li>
                @endif

                @unless ($loop->last)
                    <li class="font-mono text-sm font-medium tracking-widest text-gray-400 uppercase">
                        /
                    </li>
                @endif
                @endforeach
            </ol>
        </nav>
    @endunless
