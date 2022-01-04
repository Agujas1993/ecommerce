<header class="bg-trueGray-700">
    <div class="container-menu flex items-center h-16">
        <a class="flex flex-col items-center justify-center px-4 bg-white bg-opacity-25 text-white cursor-pointer font-semibold h-full">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path  class="inline-flex"  stroke-linecap="round"  stroke-linejoin="round"  stroke-width="2"  d="M4  6h16M4 12h16M4 18h16" />
            </svg>
            <span>
                Categorías
            </span>
        </a>
        <a href="/" class="mx-6">
        <x-jet-application-mark class="block h-9 w-auto"></x-jet-application-mark>
        </a>
        @livewire('search')
        <div class="mx-6 relative">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                    @else
                        <x-jet-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <i class="fas fa-user-circle text-white text-3xl cursor-pointer"></i>
                            </x-slot>
                            <x-slot name="content">
                                <x-jet-dropdown-link href="{{ route('login') }}">{{ __('Login') }}</x-jet-dropdown-link>
                                <x-jet-dropdown-link href="{{ route('register') }}">{{ __('Register') }}</x-jet-dropdown-link>
                            </x-slot>
                        </x-jet-dropdown>
                    @endauth
                </div>
            @endif
        </div>
        @livewire('dropdown-cart')
    </div>
</header>
