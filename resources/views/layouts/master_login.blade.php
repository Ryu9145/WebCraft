<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - ThemeMarket</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
</head>
<body>

    @yield('content')

    <script src="{{ asset('assets/js/login.js') }}"></script>
</body>
</html>