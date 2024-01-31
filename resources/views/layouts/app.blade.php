<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Codebase - Bootstrap 5 Admin Template &amp; UI Framework</title>
    <meta name="description" content="Codebase - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <!-- Icons -->
    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- Trix Editor CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
    <!-- Codebase CSS framework -->
    <link rel="stylesheet" id="css-main" href="/assets/css/codebase.min.css">
    <!-- Livewire Styles -->
    @livewireStyles
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div id="page-container" class="@if(!request()->is('login')){{ 'sidebar-o' }}@endif enable-page-overlay side-scroll page-header-modern main-content-boxed">
        @if(!request()->is('login'))
            <x-navbar />
        @endif

        <!-- Main Container -->
        <main id="main-container">
            <!-- Page Content -->
            <div class="content @if(request()->is('login')){{ 'p-0' }}@endif">
                {{ $slot }}
            </div>
        </main>

        @if(!request()->is('login'))
            <x-footer />
        @endif
    </div>

    @include('layouts.modals')

    <!-- Codebase JS Framework -->
    <script src="/assets/js/codebase.app.min.js"></script>
    <script src="/assets/js/lib/jquery.min.js"></script>
    <!-- Alpine JS Framework -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert JS Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Trix Editor JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <!-- Livewire JS -->
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    <!-- Custom JS -->
    @stack('scripts')
</body>
</html>
