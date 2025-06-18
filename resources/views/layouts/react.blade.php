<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div id="react-root"></div>
    @vite('resources/js/app.jsx')
</body>
</html>
