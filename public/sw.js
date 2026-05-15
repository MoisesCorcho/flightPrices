/**
 * Firebase App and Messaging SDKs for legacy support.
 */
importScripts('https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging-compat.js');

/**
 * Firebase project configuration.
 */
const firebaseConfig = {
    apiKey: "AIzaSyCl4f71qHNjH5v3ny4dDS7GmPAemRi6hAU",
    authDomain: "flightprices-4dfbf.firebaseapp.com",
    projectId: "flightprices-4dfbf",
    storageBucket: "flightprices-4dfbf.firebasestorage.app",
    messagingSenderId: "298414671888",
    appId: "1:298414671888:web:5beca7b05e92dc54f62295",
    measurementId: "G-B71QW442WB"
};

/**
 * Initialize Firebase and Messaging.
 */
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

/**
 * Cache configuration and assets to be pre-cached.
 */
const CACHE_NAME = 'skytrack-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/offline.html',
];

/**
 * Service Worker Installation Event.
 * Caches essential assets for offline usage.
 */
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

/**
 * Service Worker Fetch Event.
 * Serves assets from cache or falls back to offline page on network failure.
 */
self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request).then((response) => {
                return response || caches.match('/offline.html');
            });
        })
    );
});

/**
 * Firebase Cloud Messaging background message handler.
 * Displays notifications when the app is in the background.
 */
messaging.onBackgroundMessage(function(payload) {
    console.log('[ServiceWorker] Background message received: ', payload);

    const title = payload.notification?.title || payload.data?.title || 'New Flight Alert';
    const options = {
        body: payload.notification?.body || payload.data?.body,
        icon: '/apple-touch-icon.png',
        badge: '/favicon.svg',
        data: {
            url: payload.data?.url || '/'
        }
    };

    self.registration.showNotification(title, options);
});

/**
 * Notification Click Event Handler.
 * Closes the notification and opens the target URL.
 */
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});
