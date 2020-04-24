<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
    @routes
</head>
<body>
    @inertia
</body>
</html>
