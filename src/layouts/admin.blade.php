<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OBMS') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml"
        href="{{ config('company.favicon') ?? theme_asset('aurora', 'images/favicon.logo.svg') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="app" id="app">
        <nav class="navbar navbar-dark sticky-top bg-white p-0 shadow-sm">
            <a class="navbar-brand bg-white py-3" href="{{ route('admin.home') }}">
                <div class="logo">
                    <img src="{{ config('company.logo') ?? theme_asset('aurora', 'images/full.logo.svg') }}"
                        class="px-3">
                    @if (config('app.slogan'))
                        <div class="slogan small">{{ config('app.slogan', 'Open Business Management Software') }}</div>
                    @endif
                </div>
            </a>
            <div class="d-flex gap-3 flex-grow-1 justify-content-between">
                <button class="navbar-toggler shadow-sm rounded collapsed" type="button" data-toggle="collapse"
                    data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>
                <ul class="navbar-nav px-3 d-flex flex-row">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle px-3 rounded border border-gray"
                            href="#" role="button" data-toggle="dropdown" aria-expanded="false" v-pre>
                            <div>
                                {{ Auth::user()->realName }} <span
                                    class="badge badge-primary ml-2 font-weight-normal">{{ Auth::user()->number }}</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                {{ __('interface.misc.account') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('interface.actions.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="languageDropdown" class="nav-link dropdown-toggle px-3 rounded border border-gray"
                            href="#" role="button" data-toggle="dropdown" aria-expanded="false" v-pre>
                            <div>
                                <i class="bi bi-globe"></i>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="languageDropdown">
                            <a class="dropdown-item" href="{{ route('language.change', ['locale' => 'en']) }}">
                                {{ __('languages.english') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('language.change', ['locale' => 'de']) }}">
                                {{ __('languages.german') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="wrapper">
            <nav id="sidebarMenu" class="d-block shadow-sm sidebar collapse px-3 py-4">
                <h6
                    class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-0 mb-2 text-primary">
                    <span>{{ __('interface.misc.overview') }}</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item {{ Request::route()?->getName() == 'admin.home' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.home') }}"
                            title="{{ __('interface.misc.dashboard') }}" data-toggle="tooltip">
                            <i class="bi bi-house-fill"></i>
                            <span>{{ __('interface.misc.dashboard') }}</span>
                        </a>
                    </li>
                </ul>
                @if (!empty(request()->get('service_products')))
                    <h6
                        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-2 mt-3 text-primary">
                        <span>{{ __('interface.misc.products') }}</span>
                    </h6>
                    <ul class="nav flex-column">
                        @foreach (request()->get('service_products') as $product)
                            <li
                                class="nav-item {{ Request::route()?->getName() == 'admin.services.' . $product->slug ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.services.' . $product->slug) }}"
                                    title="{{ $product->name }}" data-toggle="tooltip">
                                    <i class="{{ $product->icon ?: 'bi bi-box' }}"></i>
                                    <span>{{ $product->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <h6
                    class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-2 mt-3 text-primary">
                    <span>{{ __('interface.misc.communication') }}</span>
                </h6>
                <ul class="nav flex-column">
                    <li
                        class="nav-item dropdown show {{ str_contains(Request::route()?->getName(), 'admin.support') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-expanded="false" title="{{ __('interface.misc.support') }}">
                            <div>
                                <i class="bi bi-telephone"></i>
                                <span>{{ __('interface.misc.support') }}</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>
                        <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('admin.support') }}"
                                title="{{ __('interface.misc.tickets') }}"
                                data-toggle="tooltip">{{ __('interface.misc.tickets') }}</a>
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.support.categories') }}"
                                    title="{{ __('interface.misc.categories') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.categories') }}</a>
                            @endif
                        </div>
                    </li>
                </ul>
                <h6
                    class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-2 mt-3 text-primary">
                    <span>{{ __('interface.misc.sales') }}</span>
                </h6>
                <ul class="nav flex-column">
                    <li
                        class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.customers') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customers') }}"
                            title="{{ __('interface.misc.customers') }}" data-toggle="tooltip">
                            <i class="bi bi-person"></i>
                            <span>{{ __('interface.misc.customers') }}</span>
                        </a>
                    </li>
                    <li
                        class="nav-item dropdown show {{ str_contains(Request::route()?->getName(), 'admin.invoices.customers') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-expanded="false" title="{{ __('interface.misc.invoices') }}">
                            <div>
                                <i class="bi bi-file-earmark-text"></i>
                                <span>{{ __('interface.misc.invoices') }}</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>
                        <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('admin.invoices.customers') }}"
                                title="{{ __('interface.misc.list') }}"
                                data-toggle="tooltip">{{ __('interface.misc.list') }}</a>
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.invoices.types') }}"
                                    title="{{ __('interface.misc.types') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.types') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.invoices.discounts') }}"
                                    title="{{ __('interface.misc.invoice_discounts') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.invoice_discounts') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.discounts') }}"
                                    title="{{ __('interface.misc.position_discounts') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.position_discounts') }}</a>
                            @endif
                        </div>
                    </li>
                    <li
                        class="nav-item dropdown show {{ (str_contains(Request::route()?->getName(), 'admin.contracts') ? 'active' : '') || (str_contains(Request::route()?->getName(), 'admin.discounts') ? 'active' : '') }}">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-expanded="false" title="{{ __('interface.misc.contracts') }}">
                            <div>
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>{{ __('interface.misc.contracts') }}</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>
                        <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('admin.contracts') }}"
                                title="{{ __('interface.misc.list') }}"
                                data-toggle="tooltip">{{ __('interface.misc.list') }}</a>
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.contracts.types') }}"
                                    title="{{ __('interface.misc.types') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.types') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.contracts.trackers') }}"
                                    title="{{ __('interface.misc.usage_trackers') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.usage_trackers') }}</a>
                            @endif
                        </div>
                    </li>
                    <li
                        class="nav-item dropdown show {{ str_contains(Request::route()?->getName(), 'admin.shop') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-expanded="false" title="{{ __('interface.misc.shop') }}">
                            <div>
                                <i class="bi bi-box"></i>
                                <span>{{ __('interface.misc.shop') }}</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>
                        <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('admin.shop.orders') }}"
                                title="{{ __('interface.misc.orders') }}"
                                data-toggle="tooltip">{{ __('interface.misc.orders') }}</a>
                            <a class="dropdown-item" href="{{ route('admin.shop.categories') }}"
                                title="{{ __('interface.misc.configuration') }}"
                                data-toggle="tooltip">{{ __('interface.misc.configuration') }}</a>
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.products') }}"
                                    title="{{ __('interface.misc.product_types') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.product_types') }}</a>
                            @endif
                        </div>
                    </li>
                </ul>
                <h6
                    class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-2 mt-3 text-primary">
                    <span>{{ __('interface.misc.procurement') }}</span>
                </h6>
                <ul class="nav flex-column">
                    <li
                        class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.suppliers') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.suppliers') }}"
                            title="{{ __('interface.misc.suppliers') }}" data-toggle="tooltip">
                            <i class="bi bi-person"></i>
                            <span>{{ __('interface.misc.suppliers') }}</span>
                        </a>
                    </li>
                    <li
                        class="nav-item dropdown show {{ str_contains(Request::route()?->getName(), 'admin.invoices.suppliers') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-expanded="false" title="{{ __('interface.misc.invoices') }}">
                            <div>
                                <i class="bi bi-file-earmark-text"></i>
                                <span>{{ __('interface.misc.invoices') }}</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-indicator"></i>
                        </a>
                        <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="{{ route('admin.invoices.suppliers') }}"
                                title="{{ __('interface.misc.list') }}"
                                data-toggle="tooltip">{{ __('interface.misc.list') }}</a>
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.invoices.types') }}"
                                    title="{{ __('interface.misc.types') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.types') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.invoices.discounts') }}"
                                    title="{{ __('interface.misc.invoice_discounts') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.invoice_discounts') }}</a>
                                <a class="dropdown-item" href="{{ route('admin.discounts') }}"
                                    title="{{ __('interface.misc.position_discounts') }}"
                                    data-toggle="tooltip">{{ __('interface.misc.position_discounts') }}</a>
                            @endif
                        </div>
                    </li>
                </ul>
                <h6
                    class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-2 mt-3 text-primary">
                    <span>{{ __('interface.data.company') }}</span>
                </h6>
                <ul class="nav flex-column">
                    @if (Auth::user()->role == 'admin')
                        <li
                            class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.employees') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.employees') }}"
                                title="{{ __('interface.misc.employees') }}" data-toggle="tooltip">
                                <i class="bi bi-person"></i>
                                <span>{{ __('interface.misc.employees') }}</span>
                            </a>
                        </li>
                    @endif
                    <li
                        class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.pages') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.pages') }}"
                            title="{{ __('interface.misc.custom_pages') }}" data-toggle="tooltip">
                            <i class="bi bi-list"></i>
                            <span>{{ __('interface.misc.custom_pages') }}</span>
                        </a>
                    </li>
                    <li
                        class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.filemanager') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.filemanager') }}"
                            title="{{ __('interface.misc.file_manager') }}" data-toggle="tooltip">
                            <i class="bi bi-folder"></i>
                            <span>{{ __('interface.misc.file_manager') }}</span>
                        </a>
                    </li>
                </ul>
                @if (empty(request()->get('tenant')) || Auth::user()->role == 'admin')
                    <h6
                        class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-2 mt-3 text-primary">
                        <span>{{ __('interface.misc.instance') }}</span>
                    </h6>
                    <ul class="nav flex-column">
                        @if (Auth::user()->role == 'admin')
                            <li
                                class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.settings') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.settings') }}"
                                    title="{{ __('interface.misc.parameters') }}" data-toggle="tooltip">
                                    <i class="bi bi-gear-wide-connected"></i>
                                    <span>{{ __('interface.misc.parameters') }}</span>
                                </a>
                            </li>
                            <li
                                class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.paymentgateways') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.paymentgateways') }}"
                                    title="{{ __('interface.misc.payment_gateways') }}" data-toggle="tooltip">
                                    <i class="bi bi-currency-euro"></i>
                                    <span>{{ __('interface.misc.payment_gateways') }}</span>
                                </a>
                            </li>
                            <li
                                class="nav-item dropdown show {{ str_contains(Request::route()?->getName(), 'admin.api.users') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown04"
                                    data-toggle="dropdown" aria-expanded="false"
                                    title="{{ __('interface.misc.api') }}">
                                    <div>
                                        <i class="bi bi-code-slash"></i>
                                        <span>{{ __('interface.misc.api') }}</span>
                                    </div>
                                    <i class="bi bi-chevron-down dropdown-indicator"></i>
                                </a>
                                <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                                    <a class="dropdown-item" href="{{ route('admin.api.users') }}"
                                        title="{{ __('interface.misc.users') }}"
                                        data-toggle="tooltip">{{ __('interface.misc.users') }}</a>
                                    <a class="dropdown-item" href="{{ route('admin.api.oauth-clients') }}"
                                        title="{{ __('interface.misc.oauth_clients') }}"
                                        data-toggle="tooltip">{{ __('interface.misc.oauth_clients') }}</a>
                                </div>
                            </li>
                            @if (empty(request()->get('tenant')))
                                <li
                                    class="nav-item {{ str_contains(Request::route()?->getName(), 'admin.tenants') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.tenants') }}"
                                        title="{{ __('interface.misc.tenants') }}" data-toggle="tooltip">
                                        <i class="bi bi-people"></i>
                                        <span>{{ __('interface.misc.tenants') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item dropdown show">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdown04"
                                        data-toggle="dropdown" aria-expanded="false"
                                        title="{{ __('interface.misc.monitoring') }}">
                                        <div>
                                            <i class="bi bi-activity"></i>
                                            <span>{{ __('interface.misc.monitoring') }}</span>
                                        </div>
                                        <i class="bi bi-chevron-down dropdown-indicator"></i>
                                    </a>
                                    <div class="dropdown-menu w-100" aria-labelledby="dropdown04">
                                        <a class="dropdown-item" href="{{ url('/horizon') }}" target="_blank"
                                            title="{{ __('interface.misc.horizon') }}"
                                            data-toggle="tooltip">{{ __('interface.misc.horizon') }}</a>
                                        <a class="dropdown-item" href="{{ url('/pulse') }}" target="_blank"
                                            title="{{ __('interface.misc.pulse') }}"
                                            data-toggle="tooltip">{{ __('interface.misc.pulse') }}</a>
                                    </div>
                                </li>
                            @endif
                        @endif
                    </ul>
                @endif
            </nav>

            <main role="main" class="content">
                <div class="main">
                    @if ($errors->any() || Session::has('success') || Session::has('warning') || Session::has('danger'))
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12" id="collapseGroup">
                                    <div class="alert-container mt-4">
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <div class="alert alert-warning"><i
                                                        class="bi bi-exclamation-triangle-fill"></i>
                                                    {{ $error }}</div>
                                            @endforeach
                                        @endif
                                        @if (Session::has('success'))
                                            <div class="alert alert-success mb-0"><i class="bi bi-check-circle"></i>
                                                {!! Session::get('success') !!}</div>
                                        @endif
                                        @if (Session::has('warning'))
                                            <div class="alert alert-warning mb-0"><i
                                                    class="bi bi-exclamation-triangle"></i> {!! Session::get('warning') !!}
                                            </div>
                                        @endif
                                        @if (Session::has('danger'))
                                            <div class="alert alert-danger mb-0"><i
                                                    class="bi bi-exclamation-circle"></i> {!! Session::get('danger') !!}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')

                    @if (request()->get('navigateables')->isNotEmpty())
                        <div class="container-fluid">
                            <div class="row mb-4 text-center">
                                <div class="col-md-12">
                                    @foreach (request()->get('navigateables') as $page)
                                        <a class="small" href="{{ route('cms.page.' . $page->id) }}"
                                            target="_blank">
                                            {{ __($page->title) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if (View::hasSection('actions'))
                    <div class="actions shadow-sm">
                        <div class="container-fluid my-4">
                            <div class="row">
                                <div class="col-md-12">
                                    @yield('actions')
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
    <div class="loading" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ theme_asset('aurora', 'js/app.js') }}"></script>
    <script src="{{ theme_asset('aurora', 'js/chart.umd.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.custom-file input').change(function(e) {
                var files = [];

                for (var i = 0; i < $(this)[0].files.length; i++) {
                    files.push($(this)[0].files[i].name);
                }

                $(this).next('.custom-file-label').html(files.join(', '));
            });

            const tooltip = $('.sidebar [data-toggle="tooltip"],.sidebar [data-toggle="dropdown"]').tooltip({
                placement: 'right',
                boundary: 'window',
            });

            if (Cookies.get('expanded_sidebar') !== 'no' && $(window).width() > 991) {
                $('.sidebar').addClass('show');
            }

            if ($('.sidebar').hasClass('show')) {
                tooltip.tooltip('disable');
            }

            $('.sidebar [data-toggle="dropdown"]').on('click', function() {
                $('.sidebar').addClass('show');
                tooltip.tooltip('hide');
                tooltip.tooltip('disable');
                Cookies.set('expanded_sidebar', 'yes', {
                    expires: 7,
                    path: '/',
                    sameSite: 'Lax'
                });
            });

            $('.navbar-toggler').on('click', function() {
                if (!$('.sidebar').hasClass('show')) {
                    tooltip.tooltip('disable');
                    Cookies.set('expanded_sidebar', 'yes', {
                        expires: 7,
                        path: '/',
                        sameSite: 'Lax'
                    });
                } else {
                    tooltip.tooltip('enable');
                    Cookies.set('expanded_sidebar', 'no', {
                        expires: 7,
                        path: '/',
                        sameSite: 'Lax'
                    });
                }
            });

            $('#loadingOverlay').remove();
        });
    </script>

    @yield('javascript')
</body>

</html>
