<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Admin dashboard" />
        <meta name="author" content="MagnateHub" />
        <title>{{ config('app.name') }} | @yield('title', 'Admin') </title>
        <link rel="icon" type="image/x-icon" href="{{ asset('website/images/black.png') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/daterangepicker.min.css')}}" />
        <link type="text/css" rel="stylesheet" href="{{ asset('assets/vendors/css/emojionearea.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/dataTables.bs5.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/tagify.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/tagify-data.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/quill.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/select2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/select2-theme.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}">

        <style>
            :root {
                --dashboard-hover-accent: #4452ea85;
                --dashboard-active-accent: #4452ea85;
            }

            /* SweetAlert2 – wider toasts and dialogs */
            .swal2-popup.swal2-toast {
                max-width: min(560px, 92vw) !important;
                width: auto !important;
            }
            .swal2-container .swal2-popup:not(.swal2-toast) {
                max-width: min(520px, 92vw) !important;
                width: auto !important;
            }
            .nxl-header .header-wrapper .header-left {
                min-width: 0;
                flex: 1 1 auto;
            }

            .admin-header-copy {
                min-width: 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 0;
            }

            .admin-page-title {
                margin: 0;
                font-size: 1.125rem;
                font-weight: 700;
                line-height: 1.25;
                color: #0f172a;
            }

            /* Unified hover accent for admin dashboard interactions */
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
        </style>
        
        @stack('styles')
    </head>
    <body>
        <nav class="nxl-navigation">
            <div class="navbar-wrapper">
                <div class="m-header">
                    <a href="{{ route('admin.dashboard') }}" class="b-brand">
                        <img src="{{ asset('raising/assets/images/logo/Dark-logo.png') }}" alt="MagnateHub" class="logo logo-lg" style="width: 116px; padding-left: 16px;" />
                        <img src="{{ asset('website/images/black.png') }}" alt="MagnateHub" class="logo logo-sm" />
                    </a>
                </div>
                <div class="navbar-content">
                    <ul class="nxl-navbar">
                        <li class="nxl-item nxl-caption"><label>Administration</label></li>
                        <li class="nxl-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.dashboard') }}"><span class="nxl-micon"><i class="feather-airplay"></i></span><span class="nxl-mtext">Dashboard</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.accounts.index') }}"><span class="nxl-micon"><i class="feather-shield"></i></span><span class="nxl-mtext">Accounts</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.categories.index') }}"><span class="nxl-micon"><i class="feather-grid"></i></span><span class="nxl-mtext">Categories</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.locations.index') }}"><span class="nxl-micon"><i class="feather-map-pin"></i></span><span class="nxl-mtext">Locations</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.regions.index') }}"><span class="nxl-micon"><i class="feather-map"></i></span><span class="nxl-mtext">Regions</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.projects.index') }}"><span class="nxl-micon"><i class="feather-briefcase"></i></span><span class="nxl-mtext">Listings</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.blogs.index') }}"><span class="nxl-micon"><i class="feather-edit-3"></i></span><span class="nxl-mtext">Blogs</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.blog-categories.index') }}"><span class="nxl-micon"><i class="feather-folder"></i></span><span class="nxl-mtext">Blog categories</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.blog-tags.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.blog-tags.index') }}"><span class="nxl-micon"><i class="feather-tag"></i></span><span class="nxl-mtext">Blog tags</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.email-templates.index') }}"><span class="nxl-micon"><i class="feather-mail"></i></span><span class="nxl-mtext">Email templates</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.contacts.index') }}"><span class="nxl-micon"><i class="feather-mail"></i></span><span class="nxl-mtext">Forms</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.professionals.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.professionals.index') }}"><span class="nxl-micon"><i class="feather-users"></i></span><span class="nxl-mtext">Professionals</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.professional-questions.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.professional-questions.index') }}"><span class="nxl-micon"><i class="feather-help-circle"></i></span><span class="nxl-mtext">Onboarding Questions</span></a></li>
                        <li class="nxl-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.profile') }}"><span class="nxl-micon"><i class="feather-settings"></i></span><span class="nxl-mtext">Profile</span></a></li>
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
                        <a href="javascript:void(0);" id="menu-mini-button"><i class="feather-align-left"></i></a>
                        <a href="javascript:void(0);" id="menu-expend-button" style="display:none"><i class="feather-arrow-right"></i></a>
                    </div>
                    <div class="admin-header-copy">
                        <div class="admin-page-title">@yield('page_title', 'Dashboard')</div>
                    </div>
                </div>
                <div class="header-right ms-auto">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light-brand d-none d-md-inline-flex">Overview</a>
                        <div class="dropdown nxl-h-item">
                            <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside" style="border: 1px solid #dcdee4; padding: 9px 15px; height: 39px; widht: 45px !important; border-radius: 50%;">
                                <span class="admin-empty-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                                <div class="dropdown-header">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="admin-empty-avatar" style="border: 1px solid #dcdee4; padding: 9px 15px; height: 39px; widht: 45px !important; border-radius: 50%;">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</span>
                                        <div>
                                            <h6 class="text-dark mb-0">{{ auth()->user()?->name ?? 'Admin' }}</h6>
                                            <span class="fs-12 fw-medium text-muted">{{ auth()->user()?->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                    <i class="feather-user"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('admin.projects.index') }}" class="dropdown-item">
                                    <i class="feather-briefcase"></i>
                                    <span>Listings</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('admin.logout') }}">
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

        {{-- Themed confirmation for destructive actions (replaces window.confirm) --}}
        <div class="modal fade" id="adminConfirmActionModal" tabindex="-1" aria-labelledby="adminConfirmActionTitle" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; overflow: hidden;">
                    <div class="modal-header border-0 align-items-center pb-0" style="padding: 1.25rem 1.5rem 0.5rem;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar-text avatar-md bg-soft-danger text-danger">
                                <i class="feather-alert-triangle"></i>
                            </span>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="adminConfirmActionTitle">Please confirm</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-muted pt-2 px-4 pb-1" id="adminConfirmActionMessage" style="line-height: 1.55;"></div>
                    <div class="modal-footer border-0 gap-2 px-4 pb-4 pt-2">
                        <button type="button" class="btn btn-light-brand px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger px-4" id="adminConfirmActionProceed">Yes, delete</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/vendors/js/vendors.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/daterangepicker.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/apexcharts.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/circle-progress.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/dataTables.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/dataTables.bs5.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/tagify.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/tagify-data.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/quill.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/select2.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/select2-active.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/time-tracker.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/js/emojionearea.min.js')}}"></script>    
        <script src="{{ asset('assets/js/dashboard-init.min.js')}}"></script>
        <script src="{{ asset('assets/js/apps-chat-init.min.js')}}"></script>
        <script src="{{ asset('assets/js/common-init.min.js')}}"></script>
        <script src="{{ asset('assets/js/proposal-init.min.js')}}"></script>

        <script src="{{ asset('assets/js/theme-customizer-init.min.js')}}"></script>
        <script src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>
        
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

        <script>
            (function () {
                const modalEl = document.getElementById('adminConfirmActionModal');
                const modalMessage = document.getElementById('adminConfirmActionMessage');
                const modalTitle = document.getElementById('adminConfirmActionTitle');
                const btnProceed = document.getElementById('adminConfirmActionProceed');
                if (!modalEl || !modalMessage || !modalTitle || !btnProceed) {
                    return;
                }

                let pendingForm = null;
                const modal = (typeof bootstrap !== 'undefined' && bootstrap.Modal)
                    ? new bootstrap.Modal(modalEl)
                    : null;

                function submitPendingForm() {
                    if (!pendingForm) {
                        return;
                    }
                    const form = pendingForm;
                    pendingForm = null;
                    if (modal) {
                        modal.hide();
                    }
                    if (typeof HTMLFormElement !== 'undefined' && HTMLFormElement.prototype.submit) {
                        HTMLFormElement.prototype.submit.call(form);
                    }
                }

                document.addEventListener('submit', function (e) {
                    const form = e.target;
                    if (!(form instanceof HTMLFormElement) || !form.classList.contains('js-admin-confirm-submit')) {
                        return;
                    }
                    e.preventDefault();
                    pendingForm = form;

                    const message = form.getAttribute('data-confirm-message') || 'Are you sure you want to continue?';
                    const title = form.getAttribute('data-confirm-title') || 'Please confirm';
                    const buttonLabel = form.getAttribute('data-confirm-button') || 'Yes, delete';

                    modalTitle.textContent = title;
                    modalMessage.textContent = message;
                    btnProceed.textContent = buttonLabel;

                    if (modal) {
                        modal.show();
                        return;
                    }
                    if (window.confirm(message)) {
                        submitPendingForm();
                    } else {
                        pendingForm = null;
                    }
                });

                btnProceed.addEventListener('click', function () {
                    submitPendingForm();
                });

                modalEl.addEventListener('hidden.bs.modal', function () {
                    pendingForm = null;
                });
            })();
        </script>

        @stack('scripts')
    </body>
</html>
