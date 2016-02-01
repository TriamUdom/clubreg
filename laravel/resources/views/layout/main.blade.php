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
    <link rel="stylesheet" href="/css/main.css">
    @yield('css')
  </head>
  <body>

    @include('layout/component.menu')

    <div class="container" id="main_container">
      <div class="well">
        @if(Config::get('applicationConfig.release') != 'release')
          <div class="alert alert-danger">
            <strong>ระบบนี้เป็นระบบทดสอบ หากลงทะเบียนในเวลานี้ จะถือว่าเป็นโมฆะ</strong>
          </div>
        @endif
        @yield('content')
      </div>
    </div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    @yield('script')
    @if(Config::get('applicationConfig.release') == 'release' && Config::get('applicationConfig.mode') != 'close' && Config::get('applicationConfig.mode') != 'technical_difficulties')
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-73122311-1', 'auto');
      ga('send', 'pageview');

    </script>
    @endif
  </body>
</html>
