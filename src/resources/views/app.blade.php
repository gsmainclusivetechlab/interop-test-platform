<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
    @routes
</head>
<body>
    @inertia
</body>
</html>
