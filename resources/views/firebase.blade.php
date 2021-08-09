<html>

<head>
    <title>
        fire
    </title>
    <!-- firebase integration started -->

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<!-- Firebase App is always required and must be first -->
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-app.js"></script>

<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-functions.js"></script>

<!-- firebase integration end -->

<!-- Comment out (or don't include) services that you don't want to use -->
<!-- <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-storage.js"></script> -->

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-analytics.js"></script>


</head>

<body>

</body>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
crossorigin="anonymous">
</script>

<!--################## FIREBASE SCRIPT ##################-->
{{csrf_field()}}
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.8.0/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.7.1/firebase-messaging.js"></script>

<script>
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
var firebaseConfig = {
    apiKey: "AIzaSyC0ikwpxt9iSASEtG3MC-ShcaHajoG7Cno",
    authDomain: "drugly-36099.firebaseapp.com",
    projectId: "drugly-36099",
    storageBucket: "drugly-36099.appspot.com",
    messagingSenderId: "680734245586",
    appId: "1:680734245586:web:2b4020b3663fdc281d630b",
    measurementId: "G-XE5K3HWJJY"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
// firebase.analytics();

const messaging = firebase.messaging();
messaging.requestPermission()
.then( function() {
    console.log('Permission Accepted');
    return messaging.getToken();
})
.then( function(token) {
    $.post("{{ route('updateUserFCM') }}",{'fcm_token':token,'_token':$('input[name=_token]').val()},function(data){
        console.log(token, 'Saved to database successfully.');
    });
})
.catch( function(error) {
    // console.log(error);
    console.log('Permission Denied, you will not be apple to get real time notifications!');
});

var user_token = messaging.getToken();
user_token.then(function(token) {
    console.log(token) // "Some User token"
    $('#set-token').val(token);
});

// console.log(messaging.getToken('vapidKey') );
// let userToken = AuthUser(data)
// console.log(userToken) // Promise { <pending> }

// userToken.then(function(result) {
// console.log(result) // "Some User token"
// })


</script>

</html>

