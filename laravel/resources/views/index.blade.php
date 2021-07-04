<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
{{--  <meta name="csrf-token" content="{{ csrf_token() }}">--}}
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- ファビコン -->
  <link rel="shortcut icon" href="{{ asset('/exam.svg') }}" type="image/vnd.microsoft.icon">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
{{--  <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <!-- Styles -->
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
  <!-- fontawesome -->
  <script src="https://kit.fontawesome.com/12486ecab9.js" crossorigin="anonymous"></script>
</head>
<body>
  <div id="app"></div>
  <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
