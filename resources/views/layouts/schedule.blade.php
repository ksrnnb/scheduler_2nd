<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{asset('css/styles.css')}}">
  <title>Scheduler</title>
</head>
<body>
  <div id="header" class="mb-3">
    <nav class="navbar navbar-dark sticky-top bg-primary">
      <a class="navbar-brand" href="/">Scheduler</a>
    </nav>
  </div>
  <div id="top-container">
    @yield('body')
  </div>
  <div id="footer">
    <nav class="navbar navbar-dark sticky-top bg-primary">
      <p class="text-white">This application has made by <a class="link" href="https://github.com/ksrnnb">@ksrnnb</a></p>
    </nav> 
  </div>
</body>
</html>