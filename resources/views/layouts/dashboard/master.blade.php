<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WebCraft</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/liquid-aurora-dashboard.css') }}" rel="stylesheet">
    
</head>

<body id="page-top">

    <div id="wrapper">

        @include('layouts.dashboard.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                @include('layouts.dashboard.topbar')

                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span class="copyright">
                            &copy; {{ date('Y') }} <strong>TheCraft</strong> &mdash; Crafted with <i class="fas fa-heart text-danger mx-1"></i> for Developers
                        </span>
                    </div>
                </div>
            </footer>
            </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>