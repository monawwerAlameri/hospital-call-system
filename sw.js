const CACHE_NAME = 'kkh-call-system-v3';
const CACHED_URLS = [
    '/hospital-call-system/',
    '/hospital-call-system/index.php',
    '/hospital-call-system/login.php',
    '/hospital-call-system/dashboard.php',
    '/hospital-call-system/assets/css/main.css',
    '/hospital-call-system/assets/css/pages.css',
    '/hospital-call-system/assets/js/data.js',
    '/hospital-call-system/assets/js/audio.js',
    '/hospital-call-system/assets/js/app.js'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(CACHED_URLS).catch(() => {});
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') return;
    event.respondWith(
        fetch(event.request).catch(() =>
            caches.match(event.request).then(r => r || caches.match('/hospital-call-system/'))
        )
    );
});
