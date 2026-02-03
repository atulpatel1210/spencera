<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spencera</title>
    
    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.5.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Bootstrap Icons 1.11.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- DataTables 1.13.8 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    
    <!-- DataTables Buttons 2.4.2 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    
    <!-- Select2 4.1.0 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dragdrop.css') }}">
    <style>
        body { 
            background-color: #f8f9fa; 
            overflow-x: hidden;
        }
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5.3.3 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables Core 1.13.8 -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons 2.4.2 -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- Export Dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
