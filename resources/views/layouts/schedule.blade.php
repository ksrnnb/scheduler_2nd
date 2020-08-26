<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{asset('css/styles.css')}}">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <title>Scheduler</title>
</head>
<body>
  <div id="header" class="mb-5">
    <nav class="navbar navbar-dark sticky-top bg-primary">
      <a class="navbar-brand" href="/">Scheduler</a>
    </nav>
  </div>
  <div id="top-container">
    @yield('body')
  </div>
  <div id="footer">
    <nav class="navbar navbar-dark sticky-top bg-primary">
      <p class="text-white footer">This application has made by <a class="link" href="https://github.com/ksrnnb/scheculer_2nd">@ksrnnb</a></p>
    </nav> 
  </div>
</body>
</html>