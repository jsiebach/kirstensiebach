<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('page_title')</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('partials.favicon')
    @include('partials.analytics')
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script src="{{ mix('/js/app.js') }}" defer></script>
    @yield('header-styles')
</head>
<body>
<main id="app" class="main container">
    @include('partials.header')
    @yield('body')
    @include('partials.footer')
</main>
@yield('footer-scripts')
</body>
</html>
