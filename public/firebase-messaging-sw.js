importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

// هذي البيانات حتجيبيها من حسابك في Firebase (حنوريك كيف بعدين)
firebase.initializeApp({
    apiKey: "YOUR_API_KEY",
    projectId: "YOUR_PROJECT_ID",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
});

const messaging = firebase.messaging();

// هذي الدالة تستقبل الإشعار والتطبيق مسكر وتطلعه كرسالة
messaging.onBackgroundMessage(function(payload) {
    console.log('Received background message ', payload);
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/logo.png', // مسار لوقو تطبيقك
        badge: '/images/logo.png',
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});