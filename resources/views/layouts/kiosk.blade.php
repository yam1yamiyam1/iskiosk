
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>IsKiosk: Document Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/ico/PUPLogo.ico') }}" />
    @php
        $route = Route::currentRouteName();
    @endphp

    @if($route === 'kiosk.submit')
        <link href="{{ asset('assets/css/submit.css') }}" rel="stylesheet"/>
        
    @elseif($route === 'kiosk.track.form')
        <link href="{{ asset('assets/css/track.css') }}" rel="stylesheet"/>
    @else
        <link href="{{ asset('assets/css/kioskhome.css') }}" rel="stylesheet"/>
    @endif


</head>
    <body>

        <header class="header">
            <img src="{{ asset('assets/img/header.png') }}" alt="Header Image" class="header-img">

            <div class="datetime">
                <span class="date" id="date"></span>
                <span class="time" id="time"></span>
            </div>
            </header>

                    @yield('content')
  
        <div class="bottom-section">
        
        <img src="{{ asset('assets/img/footer.png') }}" alt="Footer Tagline" class="footer-img" />
        
        </div>
<script>
    function updateDateTime() {
      const now = new Date();
      const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('date').textContent = now.toLocaleDateString('en-US', dateOptions);
      document.getElementById('time').textContent = now.toLocaleTimeString('en-US', {
        hour: 'numeric', minute: '2-digit', hour12: true
      });
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
</body>
</html>
