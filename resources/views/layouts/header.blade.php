@php use App\Helpers\PermissionHelper; @endphp
<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    <div id="kt_header" class="header header-fixed">
        <div class="container-fluid d-flex align-items-stretch justify-content-between">
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                    <ul class="menu-nav">
                        <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel menu-item-open menu-item-here menu-item-active" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">{{ __('menu.settings') }}</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">
                                    {{-- <li class="menu-item menu-item-active" aria-haspopup="true">
                                        <a href="{{ route('admin.countries.index') }}" class="menu-link">
                                    <span class="svg-icon menu-icon">
                                        <i class="fa fa-globe"></i>
                                    </span>
                                    <span class="menu-text">Countries</span>
                                    </a>
                        </li> --}}
                        <!-- Administrative Areas -->
                        @if (PermissionHelper::hasPermission('view', App\Models\Country::MODEL_NAME) || PermissionHelper::hasPermission('view', App\Models\City::MODEL_NAME) || PermissionHelper::hasPermission('view', App\Models\State::MODEL_NAME))
                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-map-marked"></i>
                                </span>
                                <span class="menu-text">{{ __('menu.administrative_areas') }}</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-right">
                                <ul class="menu-subnav">
                                    @if (PermissionHelper::hasPermission('view', App\Models\Country::MODEL_NAME))

                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('admin.countries.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fa fa-globe"></i>
                                            </span>
                                            <span class="menu-text">{{ __('menu.countries') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if (PermissionHelper::hasPermission('view', App\Models\State::MODEL_NAME))
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('admin.states.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fa fa-flag"></i>
                                            </span>
                                            <span class="menu-text">{{ __('menu.states') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if (PermissionHelper::hasPermission('view', App\Models\City::MODEL_NAME))
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('admin.cities.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fa fa-city"></i>
                                            </span>
                                            <span class="menu-text">{{ __('menu.cities') }} </span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        <!-- Branches and Academies -->
                        @if (PermissionHelper::hasPermission('view', App\Models\Branch::MODEL_NAME) || PermissionHelper::hasPermission('view', App\Models\Academy::MODEL_NAME))
                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-school"></i>
                                </span>
                                <span class="menu-text">{{ __('menu.branches_academies') }} </span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-right">
                                <ul class="menu-subnav">
                                    @if (PermissionHelper::hasPermission('view', App\Models\Branch::MODEL_NAME))

                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('admin.branches.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fa fa-code-branch"></i>
                                            </span>
                                            <span class="menu-text">{{ __('menu.branches') }} </span>
                                        </a>
                                    </li>
                                    @endif
                                    @if (PermissionHelper::hasPermission('view', App\Models\Academy::MODEL_NAME))
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('admin.academies.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fas fa-university"></i>
                                            </span>
                                            <span class="menu-text">{{ __('menu.branches_academies') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        <!-- System Management -->
                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-cogs"></i>
                                </span>
                                <span class="menu-text">{{ __('menu.system_management') }}</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-right">
                                <ul class="menu-subnav">
                                    @if (PermissionHelper::hasPermission('view', App\Models\System::MODEL_NAME))
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{ route('admin.systems.index') }}" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fas fa-network-wired"></i>
                                            </span>
                                            <span class="menu-text">{{ __('menu.systems') }}</span>
                                        </a>
                                    </li>
                                    @endif
                                    {{-- <li class="menu-item" aria-haspopup="true">
                                                    <a href="#" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <i class="fas fa-sliders-h"></i>
                                                        </span>
                                                        <span
                                                            class="menu-text">{{ __('menu.system_settings') }}</span>
                                    </a>
                        </li> --}}
                        @if (PermissionHelper::hasPermission('view', App\Models\User::MODEL_NAME))
                        <li class="menu-item" aria-haspopup="true">
                            <a href="{{ route('admin.users.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="menu-text">{{ __('menu.users') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (PermissionHelper::hasPermission('view', App\Models\Role::MODEL_NAME))
                        <li class="menu-item {{ request()->routeIs('admin.roles.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ route('admin.roles.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <span class="menu-text">{{ __('titles.roles') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (PermissionHelper::hasPermission('view', App\Models\Permission::MODEL_NAME))
                        <li class="menu-item {{ request()->routeIs('admin.permissions.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <span class="menu-text">{{ __('titles.permissions') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (PermissionHelper::hasPermission('view', App\Models\ModelEntity::MODEL_NAME))
                        <li class="menu-item {{ request()->routeIs('admin.models.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ route('admin.models.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-cube"></i> <!-- You can choose a different icon if you prefer -->
                                </span>
                                <span class="menu-text">{{ __('titles.models') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (PermissionHelper::hasPermission('view', App\Models\Sport::MODEL_NAME))
                        <li class="menu-item {{ request()->routeIs('admin.sports.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ route('admin.sports.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-dumbbell text-secondary"></i>
                                </span>
                                <span class="menu-text">{{ __('sport.titles.sports') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (PermissionHelper::hasPermission('view', App\Models\Currency::MODEL_NAME))
                        <li class="menu-item {{ request()->routeIs('admin.currencies.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ route('admin.currencies.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-coins text-secondary"></i>
                                </span>
                                <span class="menu-text">{{ __('currency.titles.currencies') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (PermissionHelper::hasPermission('view', App\Models\Item::MODEL_NAME))
                        <li class="menu-item {{ request()->routeIs('admin.items.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                            <a href="{{ route('admin.items.index') }}" class="menu-link">
                                <span class="svg-icon menu-icon">
                                    <i class="fas fa-boxes text-secondary"></i>
                                </span>
                                <span class="menu-text">{{ __('item.titles.items') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                </li>

                <li class="menu-item" aria-haspopup="true">
                    <a href="{{ route('admin.payment-methods.index') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        <span class="menu-text">{{ __('menu.payment_methods') }}</span>
                    </a>
                </li>

                </ul>
            </div>
            </li>

    </ul>
</div>
</div>
<div class="topbar">
    <div class="dropdown">
        <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">

            <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
                @if (Auth::user()->language === 'ar')
                <img class="h-20px w-20px rounded-sm" src="{{ asset('assets/media/svg/flags/151-united-arab-emirates.svg') }}" alt="Arabic" />
                @else
                <img class="h-20px w-20px rounded-sm" src="{{ asset('assets/media/svg/flags/226-united-states.svg') }}" alt="English" />
                @endif
            </div>
        </div>
        @php
        $currentLocale = Auth::check() ? Auth::user()->language : session('locale', app()->getLocale());
        @endphp
        <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-sm">
            <ul class="navi navi-hover py-4">
                <li class="navi-item">
                    <a href="{{ route('change.locale', ['locale' => 'ar']) }}" class="navi-link {{ $currentLocale === 'ar' ? 'active text-primary' : '' }}">
                        <span class="symbol symbol-20 mr-3">
                            <img src="{{ asset('assets/media/svg/flags/151-united-arab-emirates.svg') }}" alt="Arabic" />
                        </span>
                        <span class="navi-text">العربية</span>
                    </a>
                </li>
                <li class="navi-item">
                    <a href="{{ route('change.locale', ['locale' => 'en']) }}" class="navi-link {{ $currentLocale === 'en' ? 'active text-primary' : '' }}">
                        <span class="symbol symbol-20 mr-3">
                            <img src="{{ asset('assets/media/svg/flags/226-united-states.svg') }}" alt="English" />
                        </span>
                        <span class="navi-text">English</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="topbar-item">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
            <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
            <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ Auth::user()->name }}</span>
            <span class="symbol symbol-35 symbol-light-success">
                <span class="symbol-label font-size-h5 font-weight-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </span>
        </div>
    </div>
</div>
</div>

