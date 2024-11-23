<div class="main-menu menu-fixed menu-accordion menu-shadow menu-dark" data-scroll-to-active="true">
    <div class="navbar-header" style="height: unset !important;" style="background-color: #181E34">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto" style="margin: 0 auto;">
                <a class="navbar-brand" href="#">
                            <span class="brand-logo"><img alt="logo"
                                                          src="{{ asset('dashboard/app-assets/images/logo/Logo.png') }}"
                                                          style="max-width: 70% !important; margin: 0 auto; display: flex;"/>
                        </span>

                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item ">
                <a class="d-flex align-items-center" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-boxes" viewBox="0 0 16 16">
                        <path
                            d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z"/>
                    </svg>
                    <span class="menu-title text-truncate"
                          data-i18n="Charts">@lang('main')
                    </span>
                </a>
            </li>
            @can('admin')
                <li class="nav-item has-sub  " style="">
                    <a class="d-flex align-items-center" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-pie-chart">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                        </svg>
                        <span class="menu-title text-truncate" data-i18n="Charts">@lang('admins')</span></a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('managers.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('managers.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('admins')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('roles.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('roles')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('country_city')
                <li class="nav-item has-sub  " style="">
                    <a class="d-flex align-items-center" href="#">
                        <i class="fa fa-map" style="font-size:24px;"></i>
                        <span class="menu-title text-truncate"
                              data-i18n="Charts">@lang('country'),@lang('city')</span></a>
                    <ul class="menu-content">
                        <li class="nav-item {{ request()->routeIs('countries.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('countries.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('countries')</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('cities.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('cities.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('cities')</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('areas.index') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('areas.index') }}">
                                <i data-feather="file-text"></i><span
                                    class="menu-title text-truncate">@lang('الاحياء')</span>
                            </a>
                        </li>
                    </ul>

                </li>
            @endcan
            @can('categories')
                <li class="nav-item {{ request()->routeIs('categories.index') ? 'active' : '' }} ">
                    <a class="d-flex align-items-center" href="{{ route('categories.index') }}">
                        <i data-feather="file-text"></i><span
                            class="menu-title text-truncate">@lang('categories')</span>
                    </a>
                </li>
            @endcan

            @can('user')
                <li class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}  " style="">
                    <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                        <i class="fa fa-user" style="font-size:24px;"></i>
                        <span class="menu-title text-truncate"
                              data-i18n="Charts">@lang('users')</span></a>
                </li>
            @endcan
            @can('post')
                <li class="nav-item {{ request()->routeIs('posts.index') ? 'active' : '' }}  " style="">
                    <a class="d-flex align-items-center" href="{{ route('posts.index') }}">
                        <i class="fa fa-deaf" style="font-size:24px;"></i>
                        <span class="menu-title text-truncate"
                              data-i18n="Charts">@lang('posts')</span></a>
                </li>
            @endcan
            @can('offer')

                <li class="nav-item {{ request()->routeIs('offers.index') ? 'active' : '' }} ">
                    <a class="d-flex align-items-center" href="{{ route('offers.index') }}">
                        <i class="fa fa-vcard" style="font-size:20px">
                        </i>
                        <span class="menu-title text-truncate"
                              data-i18n="Charts">@lang('offers')
                    </span>
                    </a>
                </li>
            @endcan

        </ul>
    </div>
</div>
