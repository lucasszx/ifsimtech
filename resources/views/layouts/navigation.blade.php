<nav x-data="{ open: false }" class="nav nav--emphatic">
    <!-- Barra principal -->
    <div class="nav-bar">
        <div class="nav-left">

            <a href="{{ route('dashboard') }}" class="flex items-center">
                <img src="/logobranco.png" class="h-8" alt="Logo">
            </a>

            <!-- Links desktop -->
            <div class="nav-links">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-link">
                    {{ __('Dashboard') }}
                </x-nav-link>

                <x-nav-link :href="route('exams.create')" :active="request()->routeIs('exams.*')" class="nav-link">
                    {{ __('Novo Simulado') }}
                </x-nav-link>

                <x-nav-link :href="route('results.history')" :active="request()->routeIs('results.history')" class="nav-link">
                    {{ __('Histórico') }}
                </x-nav-link>
                <x-nav-link href="{{ route('study-goals.index') }}"
                            :active="request()->routeIs('study-goals.index')">
                    Metas de Estudo
                </x-nav-link>
                @if(auth()->user()?->is_admin)
                <x-nav-link :href="route('admin.questions.index')"
                            :active="request()->routeIs('admin.questions.*')"
                            class="nav-link">
                    {{ __('Gerenciar Questões') }}
                </x-nav-link>
                @endif
            </div>
        </div>

        <!-- Usuário (desktop) -->
        <div class="nav-user">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="user-trigger" aria-haspopup="menu" aria-expanded="false">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-caret" aria-hidden="true">
                            <svg viewBox="0 0 20 20" class="caret-icon">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')" class="dropdown-link">
                        {{ __('Perfil') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            class="dropdown-link"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Sair') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Hamburguer (mobile) -->
        <div class="nav-toggle">
            <button class="hamburger" @click="open = !open" :aria-expanded="open.toString()" aria-controls="nav-mobile">
                <svg viewBox="0 0 24 24" class="hamburger-icon">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu responsivo -->
  <div id="nav-mobile" class="nav-mobile" x-show="open" x-transition.origin.top.left x-cloak>
        <div class="nav-mobile-links">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="nav-mobile-link">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('exams.create')" :active="request()->routeIs('exams.*')" class="nav-mobile-link">
                {{ __('Novo Simulado') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('results.history')" :active="request()->routeIs('results.history')" class="nav-mobile-link">
                {{ __('Histórico') }}
            </x-responsive-nav-link>
        </div>

        <!-- Opções do usuário (mobile) -->
        <div class="nav-mobile-user">
            <div class="user-card">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
            </div>

            <div class="user-actions">
                <x-responsive-nav-link :href="route('profile.edit')" class="nav-mobile-link">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        class="nav-mobile-link"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
