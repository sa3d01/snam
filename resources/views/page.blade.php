<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سنــــــــــــــام</title>
    <!-- Styles -->
 <style>
@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@700&display=swap');
</style>
</head>
<body>
<div id="app" style="font-family: 'Tajawal', sans-serif;   direction: rtl; padding: 1em">
    {!!$content!!}
</div>

<!-- Scripts -->
</body>
</html>
