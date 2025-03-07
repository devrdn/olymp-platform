<header class="border-b border-gray-600">
    <div class="py-4 px-4 sm:px-6 lg:px-16 flex items-center justify-between">
        <x-application-logo />
        <nav>
            <ul class="flex flex-row items-center gap-4 font-medium">
                <li>
                    <a href="#">{{ __('Archive') }}</a>
                </li>
                <li>
                    <a href="#">{{ __('About') }}</a>
                </li>
                <li><a href="#">{{ __('FAQ') }}</a></li>
                <li>
                    <button class="bg-primary px-3 py-1 rounded-md hover:bg-primary-dark cursor-pointer">
                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</header>
