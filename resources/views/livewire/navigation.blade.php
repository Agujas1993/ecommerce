<style>
    #navigation-menu{
        height: calc(100vh - 4rem);
    }
    .navigation-link:hover .navigation-submenu{
        display: block !important;
    }
</style>

<header class="bg-trueGray-700 sticky top-0" x-data="dropdown()">
    <div class="container-menu flex items-center h-16">
        <a :class="{'bg-opacity-100 text-orange-500': open}" x-on:click="show()" class="flex flex-col items-center justify-center px-4 bg-white bg-opacity-25 text-white cursor-pointer font-semibold h-full">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path  class="inline-flex"  stroke-linecap="round"  stroke-linejoin="round"  stroke-width="2"  d="M4  6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm">
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
                        <x-jet-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <i class="fas fa-user-circle text-white text-3xl cursor-pointer"></i>
                            </x-slot>
                            <x-slot name="content">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-jet-dropdown-link href="{{ route('logout') }}"
                                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-jet-dropdown-link>
                                </form>
                                <x-jet-dropdown-link href="{{ route('profile.show') }}">{{ __('Edit profile') }}</x-jet-dropdown-link>
                            </x-slot>
                        </x-jet-dropdown>
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
    <nav id="navigation-menu" x-show="open":class="{'block': open, 'hidden': !open}"  class="bg-trueGray-700 bg-opacity-25 w-full absolute hidden">
        <div class="container-menu h-full">
            <div x-on:click.away="close()" class="grid grid-cols-4 h-full relative">
                <ul class="bg-white">
                    @foreach($categories as $category)
                        <li class="navigation-link text-trueGray-500 hover:bg-orange-500 hover:text-white">
                            <a href="" class="py-2 px-4 text-sm flex items-center">
                                <span class="flex justify-center w-9">{!! $category->icon !!}</span>
                                {{ $category->name }}
                            </a>
                            <div class="navigation-submenu bg-gray-100 absolute w-3/4 h-full top-0 right-0 hidden">
                                <x-navigation-subcategories :category="$category" />
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="col-span-3 bg-gray-100">
                    <x-navigation-subcategories :category="$categories->first()" />
                    </div>
                </div>
            </div>
    </nav>
</header>

