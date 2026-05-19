<!DOCTYPE html>
<html lang="zxx">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="keyword" content="" />
        <meta name="author" content="flexilecode" />
        <title>@yield('title', 'MagnateHub || Dashboard')</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/daterangepicker.min.css')}}" />
        <link type="text/css" rel="stylesheet" href="{{ asset('assets/vendors/css/emojionearea.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css')}}" />
        <link rel="stylesheet" href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}">

        @yield('styles')

        <style>
            :root {
                --dashboard-hover-accent: #4452ea85;
                --dashboard-active-accent: #4452ea85;
            }

            .nxl-header .header-wrapper .user-avtar {
                width: 45px;
                margin-right: 15px !important;
                border-radius: 50%;
                height: 45px;
                margin-left: 10px;
            }

            /* SweetAlert2 – wider toasts (workspace notifications) and dialogs */
            .swal2-popup.swal2-toast {
                max-width: min(560px, 92vw) !important;
                width: auto !important;
            }
            .swal2-container .swal2-popup:not(.swal2-toast) {
                max-width: min(520px, 92vw) !important;
                width: auto !important;
            }

            /* Unified hover accent for professional dashboard interactions */
            .nxl-navigation .nxl-link:hover,
            .nxl-navigation .nxl-link:focus,
            .nxl-header .dropdown-menu .dropdown-item:hover,
            .nxl-header .dropdown-menu .dropdown-item:focus,
            .nxl-content .nav-link:hover,
            .nxl-content .nav-link:focus,
            .nxl-content .single-item:hover,
            .nxl-content .single-item:focus-within,
            .nxl-content .btn-light-brand:hover,
            .nxl-content .btn-light-brand:focus,
            .nxl-content .avatar-text:hover,
            .nxl-content .avatar-text:focus,
            .nxl-content .page-header-right-open-toggle:hover,
            .nxl-content .page-header-right-close-toggle:hover{
                background-color: var(--dashboard-hover-accent) !important;
                transition: background-color 0.2s ease;
            }
            
            html.app-skin-dark .nxl-navigation .navbar-content .nxl-link {
                color: #b1b4c0 !important;
            }
        </style>

    </head>
    <body>
        @php
            use App\Helpers\SubscriptionHelper;
            $sessionType = session()->get('type');
            $panelName = match ($sessionType) {
                1 => 'Buyer Panel',
                2 => 'Seller Panel',
                3 => 'Capital Raiser Panel',
                4 => 'Broker Panel',
                default => 'Professional Panel',
            };

            $userType = session()->get('type');
            $canCreate = SubscriptionHelper::canCreateListing(session()->get('raising_id'));
            $isBuyer = ($userType == 1);
            $roleLabel = match ($userType) {
                1 => 'Buyer',
                2 => 'Seller',
                3 => 'Capital Raiser',
                4 => 'Broker / Franchise',
                default => 'Professional',
            };
        @endphp
        <nav class="nxl-navigation">
            <div class="navbar-wrapper">
                <div class="m-header">
                    <a href="{{ route('professional.dashboard') }}" class="b-brand">
                        <img src="{{ asset('raising/assets/images/logo/Dark-logo.png') }}" alt="MagnateHub" class="logo logo-lg" style="width: 116px; padding-left: 16px;" />
                        <img src="{{ asset('website/images/black.png') }}" alt="MagnateHub" class="logo logo-sm" />
                    </a>
                </div>
                <div class="navbar-content">
                    <div class="px-3 pb-3 mb-3 border-bottom border-gray-200">
                        <div class="d-flex align-items-center gap-3">
                            @if (session()->get('profile_url'))
                                <img src="{{ session()->get('profile_url') }}" alt="{{ session()->get('name', 'Professional') }}" class="rounded-circle border" style="width: 56px; height: 56px; object-fit: cover;">
                            @else
                                <span class="professional-empty-avatar" style="width: 56px; height: 56px; font-size: 20px; padding: 12px 20px; border-radius: 35px; border: 1px solid;">{{ strtoupper(substr(session()->get('name', 'P'), 0, 1)) }}</span>
                            @endif
                            <div class="overflow-hidden">
                                <div class="fw-bold text-dark text-truncate">{{ session()->get('name', 'Professional') }}</div>
                                <div class="fs-12 text-muted text-truncate">{{ session()->get('email', 'No email') }}</div>
                                <div class="fs-11 text-uppercase text-primary fw-semibold mt-1">{{ $roleLabel }}</div>
                            </div>
                        </div>
                    </div>
                    <ul class="nxl-navbar">
                        <li class="nxl-item nxl-caption"><label>Navigation</label></li>
                        <li class="nxl-item {{ request()->is('professionals/dashboard') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('professional.dashboard') }}"><span class="nxl-micon"><i class="feather-airplay"></i></span><span class="nxl-mtext">Dashboard</span></a></li>
                        <li class="nxl-item {{ request()->is('professionals/dashboard/setting') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('professional.settings') }}"><span class="nxl-micon"><i class="feather-user"></i></span><span class="nxl-mtext">My Profile</span></a></li>
                        @if (!$isBuyer)
                            <li class="nxl-item {{ request()->is('professionals/dashboard/listings*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('professional.listings.index') }}"><span class="nxl-micon"><i class="feather-briefcase"></i></span><span class="nxl-mtext">Listings</span></a></li>
                        @endif
                        <li class="nxl-item {{ request()->is('professionals/dashboard/chat*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('professional.chat') }}"><span class="nxl-micon"><i class="feather-message-square"></i></span><span class="nxl-mtext">Chats</span></a></li>
                        @if (!$isBuyer)
                            <li class="nxl-item {{ request()->is('professionals/dashboard/plan*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('professional.plans.index') }}"><span class="nxl-micon"><i class="feather-dollar-sign"></i></span><span class="nxl-mtext">Pricing</span></a></li>
                        @endif
                        <!-- <li class="nxl-item {{ request()->is('professionals/dashboard/setting') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('professional.settings') }}"><span class="nxl-micon"><i class="feather-settings"></i></span><span class="nxl-mtext">Settings</span></a></li> -->
                        <li class="nxl-item"><a class="nxl-link" href="https://magnatehub.au/" target="_blank" rel="noreferrer"><span class="nxl-micon"><i class="feather-globe"></i></span><span class="nxl-mtext">Homepage</span></a></li>
                    </ul>


                </div>
            </div>
        </nav>

        <header class="nxl-header">
            <div class="header-wrapper">
                <div class="header-left d-flex align-items-center gap-4">
                    <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                    <div class="nxl-navigation-toggle">
                        <a href="javascript:void(0);" id="menu-mini-button">
                            <i class="feather-align-left"></i>
                        </a>
                        <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                            <i class="feather-arrow-right"></i>
                        </a>
                    </div>
                    <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                        <a href="javascript:void(0);" id="nxl-lavel-mega-menu-open">
                            <i class="feather-align-left"></i>
                        </a>
                    </div>
                    <div>
                        <div class="fs-11 text-uppercase text-muted fw-semibold">{{ $panelName }}</div>
                        <div class="fw-bold text-dark">@yield('page_title', 'Dashboard')</div>
                    </div>
                </div>
                <div class="header-right ms-auto">
                    <div class="d-flex align-items-center">
                        @if(false)
                        <div class="dropdown nxl-h-item nxl-header-search d-none d-md-flex">
                            <a href="javascript:void(0);" class="nxl-head-link me-0" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="feather-search"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-search-dropdown">
                                <div class="input-group search-form">
                                    <span class="input-group-text">
                                        <i class="feather-search fs-6 text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control search-input-field" placeholder="Search workspace..." />
                                    <span class="input-group-text">
                                        <button type="button" class="btn-close"></button>
                                    </span>
                                </div>
                                <div class="dropdown-divider mt-0"></div>
                                <div class="search-items-wrapper">
                                    <div class="searching-for px-4 py-2">
                                        <p class="fs-11 fw-medium text-muted">Quick links</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('professional.dashboard') }}" class="flex-fill border rounded py-1 px-2 text-center fs-11 fw-semibold">Dashboard</a>
                                            <a href="{{ route('professional.listings.index') }}" class="flex-fill border rounded py-1 px-2 text-center fs-11 fw-semibold">Listings</a>
                                            <a href="{{ route('professional.chat') }}" class="flex-fill border rounded py-1 px-2 text-center fs-11 fw-semibold">Inbox</a>
                                            <a href="{{ route('professional.plans.index') }}" class="flex-fill border rounded py-1 px-2 text-center fs-11 fw-semibold">Pricing</a>
                                            <a href="{{ route('professional.settings') }}" class="flex-fill border rounded py-1 px-2 text-center fs-11 fw-semibold">Settings</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown nxl-h-item d-none d-sm-flex">
                            <a href="javascript:void(0);" class="nxl-head-link me-0" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                                <i class="feather-bell"></i>
                                <span class="badge bg-danger nxl-h-badge">{{ session()->get('plan_type') == 0 ? '1' : '2' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                                <div class="d-flex justify-content-between align-items-center notifications-head">
                                    <h6 class="fw-bold text-dark mb-0">Notifications</h6>
                                    <a href="javascript:void(0);" class="fs-11 text-success text-end ms-auto">
                                        <i class="feather-check"></i>
                                        <span>Workspace</span>
                                    </a>
                                </div>
                                <div class="notifications-item">
                                    <div class="avatar-text rounded me-3 border bg-soft-primary text-primary">
                                        <i class="feather-briefcase"></i>
                                    </div>
                                    <div class="notifications-desc">
                                        <a href="{{ route('professional.listings.index') }}" class="font-body text-truncate-2-line">
                                            <span class="fw-semibold text-dark">Listings</span> Manage your latest professional listings from the dashboard.
                                        </a>
                                        <div class="notifications-date text-muted border-bottom border-bottom-dashed">Updated just now</div>
                                    </div>
                                </div>
                                <div class="notifications-item">
                                    <div class="avatar-text rounded me-3 border bg-soft-success text-success">
                                        <i class="feather-message-square"></i>
                                    </div>
                                    <div class="notifications-desc">
                                        <a href="{{ route('professional.chat') }}" class="font-body text-truncate-2-line">
                                            <span class="fw-semibold text-dark">Inbox</span> Open buyer enquiries and keep conversations moving.
                                        </a>
                                        <div class="notifications-date text-muted border-bottom border-bottom-dashed">Live panel</div>
                                    </div>
                                </div>
                                <div class="text-center notifications-footer">
                                    <a href="{{ route('professional.chat') }}" class="fs-13 fw-semibold text-dark">Open Messages</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="nxl-h-item dark-light-theme">
                            <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                                <i class="feather-moon"></i>
                            </a>
                            <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                                <i class="feather-sun"></i>
                            </a>
                        </div>
                        <div class="dropdown nxl-h-item">
                            <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                                @if (session()->get('profile_url'))
                                    <img src="{{ session()->get('profile_url') }}" alt="user-image" class="img-fluid user-avtar me-0" />
                                @else
                                    <span class="professional-empty-avatar" style="width: 49px !important; height: 48px; font-size: 20px; padding: 8px 18px; border-radius: 30px; border: 1px solid; display: block; color: #6c7986;">{{ strtoupper(substr(session()->get('name', 'P'), 0, 1)) }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                                <div class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        @if (session()->get('profile_url'))
                                            <img src="{{ session()->get('profile_url') }}" alt="user-image" class="img-fluid user-avtar" />
                                        @else
                                            <span class="professional-empty-avatar me-3" style="width: 49px !important; height: 48px; font-size: 20px; padding: 8px 18px; border-radius: 30px; border: 1px solid; display: block;">{{ strtoupper(substr(session()->get('name', 'P'), 0, 1)) }}</span>
                                        @endif
                                        <div>
                                            <h6 class="text-dark mb-0">{{ session()->get('name') }} <span class="badge bg-soft-success text-success ms-1">PRO</span></h6>
                                            <span class="fs-12 fw-medium text-muted">{{ session()->get('email') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="dropdown">
                                        <span class="hstack">
                                            <i class="wd-10 ht-10 border border-2 border-gray-1 bg-success rounded-circle me-2"></i>
                                            <span>Active</span>
                                        </span>
                                        <!-- <i class="feather-chevron-right ms-auto me-0"></i> -->
                                    </a>
                                    @if (false)
                                    <div class="dropdown-menu">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            <span class="hstack">
                                                <i class="wd-10 ht-10 border border-2 border-gray-1 bg-success rounded-circle me-2"></i>
                                                <span>{{ $panelName }}</span>
                                            </span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('professional.settings') }}" class="dropdown-item">
                                    <i class="feather-user"></i>
                                    <span>Profile Details</span>
                                </a>
                                <a href="{{ route('professional.chat') }}" class="dropdown-item">
                                    <i class="feather-message-square"></i>
                                    <span>Inbox</span>
                                </a>
                                @if (!$isBuyer)
                                    <a href="{{ route('professional.plans.index') }}" class="dropdown-item">
                                        <i class="feather-dollar-sign"></i>
                                        <span>Billing Details</span>
                                    </a>
                                    <a href="{{ route('professional.listings.index') }}" class="dropdown-item">
                                        <i class="feather-briefcase"></i>
                                        <span>Listings</span>
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('professional.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="feather-log-out"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')

        <script src="{{ asset('assets/vendors/js/vendors.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/daterangepicker.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/apexcharts.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/circle-progress.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/time-tracker.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/emojionearea.min.js')}}"></script>    
        <script src="{{ asset('assets/js/common-init.min.js')}}"></script>
        <script src="{{ asset('assets/js/dashboard-init.min.js')}}"></script>
        <script src="{{ asset('assets/js/apps-chat-init.min.js')}}"></script>
        <script src="{{ asset('assets/js/theme-customizer-init.min.js')}}"></script>
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
        
        @php
            $formatToastMessage = function ($value) {
            if ($value instanceof \Illuminate\Support\MessageBag) {
                $value = implode(' ', $value->all());
            } elseif (is_array($value)) {
                $value = implode(' ', \Illuminate\Support\Arr::flatten($value));
            }

            // Strip any HTML (for example accidental input markup from server-side messages).
            return trim(strip_tags((string) $value));
            };

            $toastSuccess = session()->has('success') ? $formatToastMessage(session('success')) : null;
            $toastError = session()->has('error') ? $formatToastMessage(session('error')) : null;
            $toastWarning = session()->has('warning') ? $formatToastMessage(session('warning')) : null;
            $toastInfo = session()->has('info') ? $formatToastMessage(session('info')) : null;
            $toastValidation = $errors->any() ? $formatToastMessage(implode(' ', $errors->all())) : null;
        @endphp

        <script>
            function createThemeToast(timer) {
                return Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: timer,
                    timerProgressBar: true,
                    didOpen: function (toast) {
                        sanitizeSwalPopupElements(toast);
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            }
        </script>

        @if($toastSuccess !== null && $toastSuccess !== '')
            <script>
                const Toast = createThemeToast(3000);
                Toast.fire({
                    icon: 'success',
                    text: @json($toastSuccess)
                });
            </script>
        @endif
        
        @if($toastError !== null && $toastError !== '')
            <script>
                const Toast = createThemeToast(4000);
                Toast.fire({
                    icon: 'error',
                    text: @json($toastError)
                });
            </script>
        @endif

        @if($toastValidation !== null && $toastValidation !== '')
            <script>
                const Toast = createThemeToast(5500);
                Toast.fire({
                    icon: 'error',
                    text: @json($toastValidation)
                });
            </script>
        @endif

        @if($toastWarning !== null && $toastWarning !== '')
            <script>
                const Toast = createThemeToast(3000);
                Toast.fire({
                    icon: 'warning',
                    text: @json($toastWarning)
                });
            </script>
        @endif

        @if($toastInfo !== null && $toastInfo !== '')
            <script>
                const Toast = createThemeToast(3000);
                Toast.fire({
                    icon: 'info',
                    text: @json($toastInfo)
                });
            </script>
        @endif

        @yield('scripts')
    </body>

</html>









