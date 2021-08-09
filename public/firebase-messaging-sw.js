// Add Firebase products that you want to use
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

// Firebase SDK
firebase.initializeApp({
    apiKey: 'api-key',
    authDomain: 'project-id.firebaseapp.com',
    databaseURL: 'https://project-id.firebaseio.com',
    projectId: 'project-id',
    storageBucket: 'project-id.appspot.com',
    messagingSenderId: 'sender-id',
    appId: 'app-id',
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message has received : ", payload);
    const title = "First, solve the problem.";
    const options = {
        body: "Push notificaiton!",
        icon: "/icon.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});
