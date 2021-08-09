<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ config('app.name') }} | {{ Request::segment(2) }}</title>
  <!-- Favicon icon -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('uploads/favicon.ico') }}">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/fontawesome.min.css') }}">

  @yield('style')
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/components.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
  <div class="loading"></div>
 <div id="app">
  <div class="main-wrapper">
    <div class="navbar-bg"></div>
    @include('layouts/backend/partials/header')

    @include('layouts/backend/partials/sidebar')

    <!-- Main Content -->
    <div class="main-content">
      <section class="section">
       @yield('content')
     </section>
   </div>


   <!-- <footer class="main-footer">
    <div class="footer-left">
      {{ __('Copyright') }} &copy; {{ date('Y') }} <div class="bullet"></div> {{ __('Powered By') }} <a href="https://codecanyon.net/user/amcoders">{{ __('AMCoders') }}</a>
    </div>
    <div class="footer-right">
    </div>
  </footer> -->
</div>
</div>

@yield('extra')
@stack('extra')
<!-- General JS Scripts -->
@if(Auth::user()->role_id == 3)
@if (Amcoders\Plugin\Plugin::is_active('plan')) {
<input id="saasurls" type="hidden" value="{{ route('store.plancheck') }}">
@endif
@endif
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

<script src="{{ asset('admin/assets/js/jquery-3.5.1.min.js') }}"></script>

<script src="{{ asset('admin/assets/js/popper.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.all.min.js') }}"></script>

<!-- Template JS File -->
<script src="{{ asset('admin/assets/js/scripts.js') }}"></script>
<script src="{{ asset('admin/assets/js/custom.js') }}"></script>
@yield('script')
<script src="{{ asset('admin/js/main.js') }}"></script>
@if(Auth::user()->role_id == 3)
<script src="{{ theme_asset('khana/public/js/saas/saas.js') }}"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyAYNXGjk2sUl2kWAoJDJNW6pGh9Nm6NjNs",
        authDomain: "restaurant-92217.firebaseapp.com",
        projectId: "restaurant-92217",
        storageBucket: "restaurant-92217.appspot.com",
        messagingSenderId: "601371732796",
        appId: "1:601371732796:web:9820957baca1bda776cc39",
        measurementId: "G-XHGPX2Q68S"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initNotification() {
        messaging
            .requestPermission().then(function () {

                return messaging.getToken()
            }).then(function (response) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route("save-device.token") }}',
                    type: 'POST',
                    data: {
                        token: response
                    },
                    dataType: 'JSON',
                    success: function (response) {

                        console.log('Device token saved.');
                    },
                    error: function (error) {
                        console.log(error);
                    },
                });
            }).catch(function (error) {
                console.log(error);
            });
    }

    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });

    initNotification();
</script>

</body>
</html>
