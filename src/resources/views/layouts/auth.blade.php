<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
@yield('content')
</body>
</html>
