<div class="bg-white shadow h-16 flex items-center justify-between px-4">
    <!-- Izquierda: Logo y enlaces -->
    <div class="flex items-center space-x-8">
        <!-- Logo -->
        <div class="flex-shrink-0" style="margin-left: 200px;">
            <a href="{{ route('dashboard') }}">
                <img src="https://github.com/juancruzfilippini/logo-presupuestos/blob/main/20241210_132650__1_-removebg-preview.png?raw=true"
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
            class="text-sm font-medium hover:text-gray-900">
            {{ __('Generar reporte') }}
        </x-nav-link>

        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <div class="dropdown" style="position: relative;">
                <button
                    class="border border-gray inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 dropdown-toggle"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div>
                        {{ Auth::user()->name ?? '' }}
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <x-dropdown-link :href="route('profile.edit')"
                        class="no-underline block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100">
                        {{ Auth::user()->name }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="no-underline block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100">
                            {{ __('Cerrar Sesión') }}
                        </x-dropdown-link>
                    </form>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>