<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/images/FITA.png">
    <title>Hệ thống quản lý sinh viên trực tuyến</title>
</head>
<body>
<div id="app"></div>
@production
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    @endphp
    <script type="module" src="/build/{{$manifest['resources/js/app.ts']['file']}}"></script>
    @else
        @vite('resources/js/app.ts')
@endproduction
</body>
</html>