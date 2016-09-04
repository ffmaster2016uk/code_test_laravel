<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Directory</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>
<body>
<div class="container">
    <h1>My Directory</h1>
    @yield('content')

</div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
</body>
</html>