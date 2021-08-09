@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <button onclick="initNotification()"
                    class="btn btn-danger btn-flat">Generate Device Token
                </button>
            <div class="card mt-3">
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form action="{{ route('send.notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Body</label>
                            <textarea name="body" class="form-control" ></textarea>
                        </div>

                        <div class="form-group">
                          <button type="submit" class="btn btn-dark btn-block">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
</script>
@endsection
