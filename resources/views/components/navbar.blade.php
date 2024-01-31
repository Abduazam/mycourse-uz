<!-- Sidebar -->
<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Side Header -->
        <div class="content-header justify-content-lg-center">
            <!-- Logo -->
            <div>
                <span class="smini-visible fw-bold tracking-wide fs-lg">
                    c<span class="text-primary">b</span>
                </span>
                <a class="link-fx fw-bold tracking-wide mx-auto" href="{{ url('/') }}">
                    <span class="smini-hidden">
                        <i class="fa fa-fire text-primary"></i>
                        <span class="fs-4 text-dual">Hilal</span><span class="fs-4 text-primary">Arabic</span>
                    </span>
                </a>
            </div>

            <!-- Options -->
            <div>
                <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-fw fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
            <!-- Side User -->
            <div class="content-side content-side-user px-0 py-0">
                <div class="smini-visible-block animated fadeIn px-3">
                    <img class="img-avatar img-avatar32" src="/assets/media/avatars/avatar.jpg" alt="">
                </div>

                <!-- Visible only in normal mode -->
                <div class="smini-hidden text-center mx-auto">
                    <a class="img-link" href="{{ url('/') }}">
                        <img class="img-avatar" src="/assets/media/avatars/avatar.jpg" alt="">
                    </a>
                    <ul class="list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a class="link-fx text-dual fs-sm fw-semibold text-uppercase" href="#">{{ auth()?->user()?->name }}</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-fx text-dual" data-toggle="layout" data-action="dark_mode_toggle" href="javascript:void(0)">
                                <i class="fa fa-burn"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-fx text-dual" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out-alt"></i>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('/')){{ 'active' }}@endif" href="{{ url('/') }}">
                            <i class="nav-main-link-icon fa fa-house-user"></i>
                            <span class="nav-main-link-name">Home</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">Telegram bot</li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('questions*')){{ 'active' }}@endif" href="{{ route('questions.index') }}">
                            <i class="nav-main-link-icon far fa-circle-question"></i>
                            <span class="nav-main-link-name">Questions</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('messages*')){{ 'active' }}@endif" href="{{ route('messages.index') }}">
                            <i class="nav-main-link-icon far fa-comments"></i>
                            <span class="nav-main-link-name">Messages</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('reminders*')){{ 'active' }}@endif" href="{{ route('reminders.index') }}">
                            <i class="nav-main-link-icon far fa-bell"></i>
                            <span class="nav-main-link-name">Reminders</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">Students</li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('users*')){{ 'active' }}@endif" href="{{ route('users.index') }}">
                            <i class="nav-main-link-icon far fa-user"></i>
                            <span class="nav-main-link-name">Users</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('students*')){{ 'active' }}@endif" href="{{ route('students.index') }}">
                            <i class="nav-main-link-icon fa fa-user-graduate"></i>
                            <span class="nav-main-link-name">Students</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('applications*')){{ 'active' }}@endif" href="{{ route('applications.index') }}">
                            <i class="nav-main-link-icon si si-info"></i>
                            <span class="nav-main-link-name">Application</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('user-tasks*')){{ 'active' }}@endif" href="{{ route('user-tasks.index') }}">
                            <i class="nav-main-link-icon si si-note"></i>
                            <span class="nav-main-link-name">User tasks</span>
                        </a>
                    </li>
                    <li class="nav-main-heading">Courses</li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('courses*')){{ 'active' }}@endif" href="{{ route('courses.index') }}">
                            <i class="nav-main-link-icon fa fa-book"></i>
                            <span class="nav-main-link-name">Courses</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('lessons*')){{ 'active' }}@endif" href="{{ route('lessons.index') }}">
                            <i class="nav-main-link-icon fa fa-graduation-cap"></i>
                            <span class="nav-main-link-name">Lessons</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(request()->is('tasks*')){{ 'active' }}@endif" href="{{ route('tasks.index') }}">
                            <i class="nav-main-link-icon fa fa-pen-ruler"></i>
                            <span class="nav-main-link-name">Tasks</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Header -->
<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="space-x-1">
            <!-- Toggle Sidebar -->
            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <!-- Right Section -->
        <div class="space-x-1">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user d-sm-none"></i>
                    <span class="d-none d-sm-inline-block fw-semibold">{{ auth()?->user()?->name }}</span>
                    <i class="fa fa-angle-down opacity-50 ms-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
                    <div class="px-2 py-3 bg-body-light rounded-top">
                        <h5 class="h6 text-center mb-0">
                            {{ auth()?->user()?->name }}
                        </h5>
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="be_pages_generic_profile.html">
                            <span>Profile</span>
                            <i class="fa fa-fw fa-user opacity-25"></i>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                            <span>Settings</span>
                            <i class="fa fa-fw fa-wrench opacity-25"></i>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <span>Sign Out</span>
                            <i class="fa fa-fw fa-sign-out-alt opacity-25"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-flag"></i>
                    <span class="text-primary">&bull;</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications">
                    <div class="px-2 py-3 bg-body-light rounded-top">
                        <h5 class="h6 text-center mb-0">
                            Notifications
                        </h5>
                    </div>
                    <ul class="nav-items my-2 fs-sm">
                        <li>
                            <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                <div class="flex-shrink-0 me-2 ms-3">
                                    <i class="fa fa-fw fa-check text-success"></i>
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <p class="fw-medium mb-1">You’ve upgraded to a VIP account successfully!</p>
                                    <div class="text-muted">15 min ago</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                <div class="flex-shrink-0 me-2 ms-3">
                                    <i class="fa fa-fw fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <p class="fw-medium mb-1">Please check your payment info since we can’t validate them!</p>
                                    <div class="text-muted">50 min ago</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                <div class="flex-shrink-0 me-2 ms-3">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <p class="fw-medium mb-1">Web server stopped responding and it was automatically restarted!</p>
                                    <div class="text-muted">4 hours ago</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                <div class="flex-shrink-0 me-2 ms-3">
                                    <i class="fa fa-fw fa-exclamation-triangle text-warning"></i>
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <p class="fw-medium mb-1">Please consider upgrading your plan. You are running out of space.</p>
                                    <div class="text-muted">16 hours ago</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="text-dark d-flex py-2" href="javascript:void(0)">
                                <div class="flex-shrink-0 me-2 ms-3">
                                    <i class="fa fa-fw fa-plus text-primary"></i>
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <p class="fw-medium mb-1">New purchases! +$250</p>
                                    <div class="text-muted">1 day ago</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="p-2 bg-body-light rounded-bottom">
                        <a class="dropdown-item text-center mb-0" href="javascript:void(0)">
                            <i class="fa fa-fw fa-flag opacity-50 me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
