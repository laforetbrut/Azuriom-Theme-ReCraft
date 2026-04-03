<nav class="rc-navbar rc-navbar-{{ theme_config('navbar_style') ?: 'glass' }}{{ theme_config('navbar_sticky') !== '0' ? ' rc-navbar-sticky' : '' }}{{ theme_config('navbar_shadow') !== '0' ? ' rc-navbar-shadow' : '' }}" id="rcNavbar" style="--navbar-height: {{ theme_config('navbar_height') ?: '72' }}px;{{ theme_config('navbar_border_color') ? 'border-bottom-color:' . theme_config('navbar_border_color') . ';' : '' }}">
    <div class="container">
        {{-- Brand --}}
        <a href="{{ route('home') }}" class="rc-navbar-brand">
            <img src="{{ favicon() }}" alt="{{ site_name() }}" style="height: {{ theme_config('navbar_logo_size') ?: '40' }}px; width: auto;">
            @if(theme_config('navbar_show_name') !== '0')<span>{{ site_name() }}</span>@endif
        </a>

        {{-- Mobile Toggle --}}
        <button class="rc-navbar-toggle" id="navbarToggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        {{-- Nav Collapse --}}
        <div class="rc-navbar-collapse" id="navbarCollapse">
            <ul class="rc-navbar-nav">
                @foreach($navbar ?? [] as $element)
                    @if($element->isDropdown())
                        <li class="rc-nav-dropdown">
                            <a href="#" class="rc-nav-link @if($element->isCurrent()) active @endif">
                                {{ $element->name }}
                                <i class="bi bi-chevron-down" style="font-size:0.65em;opacity:0.6;"></i>
                            </a>
                            <div class="rc-nav-dropdown-menu">
                                @foreach($element->elements as $childElement)
                                    <a href="{{ $childElement->getLink() }}" @if($childElement->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                        {{ $childElement->name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ $element->getLink() }}" class="rc-nav-link @if($element->isCurrent()) active @endif" @if($element->new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $element->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="rc-navbar-right">
                @auth
                    <div class="rc-nav-dropdown">
                        <a href="#" class="rc-nav-user">
                            <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down" style="font-size:0.6em;opacity:0.6;"></i>
                        </a>
                        <div class="rc-nav-dropdown-menu">
                            <a href="{{ route('profile.index') }}">
                                <i class="bi bi-person"></i> {{ trans('messages.nav.profile') }}
                            </a>

                            @foreach(plugins()->getUserNavItems() ?? [] as $navId => $navItem)
                                <a href="{{ route($navItem['route']) }}">
                                    {{ trans($navItem['name']) }}
                                </a>
                            @endforeach

                            @if(Auth::user()->hasAdminAccess())
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> {{ trans('messages.nav.admin') }}
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:var(--danger);">
                                <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">
                        {{ trans('auth.login') }}
                    </a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            {{ trans('auth.register') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>
