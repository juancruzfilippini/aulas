<div class="bg-white shadow h-16 flex items-center justify-between px-4">
    <!-- Izquierda: Logo y enlaces -->
    <div class="flex items-center space-x-8">
        <!-- Logo -->
        <div class="flex-shrink-0"  style="margin-left: 200px;">
            <a href="{{ route('dashboard') }}">
                <img src="https://github.com/juancruzfilippini/logo-presupuestos/blob/main/icono_proyeccion.png?raw=true"
                    alt="Logo de Aulas" class="block h-9 w-auto">
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden sm:flex space-x-8">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Calendario') }}
            </x-nav-link>
            <x-nav-link :href="route('events.register')" :active="request()->routeIs('events.register')">
                {{ __('Registrar evento') }}
            </x-nav-link>
            <x-nav-link :href="route('events.manage')" :active="request()->routeIs('events.manage')">
                {{ __('Eliminar evento') }}
            </x-nav-link>
        </div>
    </div>

    <!-- Derecha: Enlace de reporte y menú -->
    <div class="flex items-center space-x-4">
        <!-- Generar reporte -->
        <x-nav-link :href="route('report.view')" :active="request()->routeIs('report.view')"
            class="text-sm font-medium text-gray-700 hover:text-gray-900">
            {{ __('Generar reporte') }}
        </x-nav-link>

        <!-- Settings Dropdown -->
        <div class="relative">
            <x-dropdown>
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                     this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</div>