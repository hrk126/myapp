<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/destyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <!-- @for ($i=1; $i < 13; $i++)
          <link rel="preload" href="images/{!! $i !!}.jpg" as="image">
        @endfor -->
        <title>MyApp</title>
    </head>
    <body>
      <div id="app"></div>
      <script src="{{ mix('js/manifest.js') }}"></script>
      <script src="{{ mix('js/vendor.js') }}"></script>
      <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
