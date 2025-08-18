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
            <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                <a href="javascript:;" class="menu-link menu-toggle">
                    <span class="menu-text">{{ __('menu.features') }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="menu-submenu menu-submenu-fixed menu-submenu-left" style="width:1000px">
                    <div class="menu-subnav">
                        <ul class="menu-content">
                            {{-- <li class="menu-item">
                                <h3 class="menu-heading menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Task Reports</span>
                                    <i class="menu-arrow"></i>
                                </h3>
                                <ul class="menu-inner">
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M5.84026576,8 L18.1597342,8 C19.1999115,8 20.0664437,8.79732479 20.1528258,9.83390904 L20.8194924,17.833909 C20.9112219,18.9346631 20.0932459,19.901362 18.9924919,19.9930915 C18.9372479,19.9976952 18.8818364,20 18.8264009,20 L5.1735991,20 C4.0690296,20 3.1735991,19.1045695 3.1735991,18 C3.1735991,17.9445645 3.17590391,17.889153 3.18050758,17.833909 L3.84717425,9.83390904 C3.93355627,8.79732479 4.80008849,8 5.84026576,8 Z M10.5,10 C10.2238576,10 10,10.2238576 10,10.5 L10,11.5 C10,11.7761424 10.2238576,12 10.5,12 L13.5,12 C13.7761424,12 14,11.7761424 14,11.5 L14,10.5 C14,10.2238576 13.7761424,10 13.5,10 L10.5,10 Z" fill="#000000" />
                                                        <path d="M10,8 L8,8 L8,7 C8,5.34314575 9.34314575,4 11,4 L13,4 C14.6568542,4 16,5.34314575 16,7 L16,8 L14,8 L14,7 C14,6.44771525 13.5522847,6 13,6 L11,6 C10.4477153,6 10,6.44771525 10,7 L10,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                            <span class="menu-text">Latest Tasks</span>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                            <li class="menu-item">
                                {{-- <h3 class="menu-heading menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Profit Margins</span>
                                    <i class="menu-arrow"></i>
                                </h3> --}}
                                 {{-- <ul class="menu-inner">
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">Overall Profits</span>
                                        </a>
                                    </li>

                                </ul>  --}}
            </li>
            {{-- <li class="menu-item">
                <h3 class="menu-heading menu-toggle">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Staff Management</span>
                    <i class="menu-arrow"></i>
                </h3>
                <ul class="menu-inner">
                    <li class="menu-item" aria-haspopup="true">
                        <a href="javascript:;" class="menu-link">
                            <i class="menu-bullet menu-bullet-dot">
                                <span></span>
                            </i>
                            <span class="menu-text">Top Management</span>
                        </a>
                    </li>
                </ul>
            </li> --}}
            {{-- <li class="menu-item">
                <h3 class="menu-heading menu-toggle">
                    <i class="menu-bullet menu-bullet-dot">
                        <span></span>
                    </i>
                    <span class="menu-text">Tools</span>
                    <i class="menu-arrow"></i>
                </h3>
                <ul class="menu-inner">
                    <li class="menu-item" aria-haspopup="true">
                        <a href="javascript:;" class="menu-link">
                            <span class="menu-text">Analytical Reports</span>
                        </a>
                    </li>
                </ul>
            </li> --}}
            </ul>
        </div>
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

