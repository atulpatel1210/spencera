<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spencera</title>
    
    <!-- Bootstrap 5.3.3 -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome 6.5.2 -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome-6.5.2.min.css') }}">
    
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.min.css') }}">
    
    <!-- DataTables 1.13.8 -->
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    
    <!-- DataTables Buttons 2.4.2 -->
    <link rel="stylesheet" href="{{ asset('css/buttons.bootstrap5.min.css') }}">
    
    <!-- Select2 4.1.0 -->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- Select2 Bootstrap 5 Theme -->
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" />
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="stylesheet" href="{{ asset('css/plus-jakarta-sans.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dragdrop.css') }}">
    <style>
        body { 
            background-color: #f6f8fb; 
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }
        .main-content-wrapper { background-color: #f6f8fb !important; }
    </style>
</head>
<body>

<div id="sidebar-overlay" class="overlay"></div>

<div class="app-container">
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        @include('layouts.sidebar')
    </div>
    
    <div class="main-content-wrapper">
        @include('layouts.header')

        <main class="p-3">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
</div>

<!-- jQuery 3.7.1 -->
<script src="{{ asset('js/jquery.min.js') }}"></script>

<!-- Bootstrap 5.3.3 -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>

<!-- DataTables Core 1.13.8 -->
<script src="{{ asset('js/jquery.dataTables.min.js') }}" defer></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}" defer></script>

<!-- DataTables Buttons 2.4.2 -->
<script src="{{ asset('js/dataTables.buttons.min.js') }}" defer></script>
<script src="{{ asset('js/buttons.bootstrap5.min.js') }}" defer></script>
<script src="{{ asset('js/buttons.html5.min.js') }}" defer></script>
<script src="{{ asset('js/buttons.print.min.js') }}" defer></script>

<!-- Export Dependencies -->
<script src="{{ asset('js/jszip.min.js') }}" defer></script>
<script src="{{ asset('js/pdfmake.min.js') }}" defer></script>
<script src="{{ asset('js/vfs_fonts.js') }}" defer></script>

<!-- Select2 -->
<script src="{{ asset('js/select2.min.js') }}" defer></script>

<script>
    $(document).ready(function() {
        function toggleSidebar() {
            if ($(window).width() >= 992) {
                $('#sidebar-wrapper').toggleClass('collapsed');
            } else {
                $('#sidebar-wrapper').toggleClass('show');
                $('#sidebar-overlay').toggleClass('show');
                $('body').toggleClass('overflow-hidden');
            }
        }

        $('#sidebar-toggle, #sidebar-overlay, #sidebar-close').on('click', toggleSidebar);
        
        // Handle window resize to clean up states
        $(window).on('resize', function() {
            if ($(window).width() >= 992) {
                $('#sidebar-overlay').removeClass('show');
                $('body').removeClass('overflow-hidden');
                $('#sidebar-wrapper').removeClass('show');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
