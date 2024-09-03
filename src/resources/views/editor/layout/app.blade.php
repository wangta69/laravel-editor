<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache"> 
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--Title and Meta--}}
    {{--@meta--}}
    @yield('meta_tags')
    <title>@yield('title', config('app.name', 'Laravel Editor'))</title>

    @yield('styles')
    @yield('head')
</head>
<body>
    @yield('page')
</body>
@yield('scripts')
</html>
