<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spencera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            background-color: #343a40;
            color: #fff;
        }
        .sidebar a { color: #adb5bd; display: block; padding: 10px; text-decoration: none; }
        .sidebar a:hover { background-color: #495057; color: #fff; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-2 sidebar">
            @include('layouts.sidebar')
        </div>
        <div class="col-10 p-0">
            @include('layouts.header')

            <main class="p-3 main-content">
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
