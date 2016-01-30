<!DOCTYPE html>
<html>
  <head>
    <title>งานกิจกรรมพัฒนาผู้เรียน</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/thaisarabunnew.css" type="text/css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/css/tpl.css">
    @yield('css')
  </head>
  <body>

    @include('layout/component.menu')

    <div class="container">
      <div class="well">
        @yield('content')
      </div>
    </div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    @yield('script')
  </body>
</html>
