// Bump the version to make every client drop the old cache on next visit.
const CACHE = 'baby-tracker-v1';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.add(OFFLINE_URL)));
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE).map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

// Pages are always fetched live — they carry the session, CSRF token and
// up-to-the-minute entries, so serving one stale from a cache would be
// wrong. The cache only exists to show a friendly offline page instead of
// the browser's error screen when there's no connection. Everything else
// (assets, Livewire requests, uploads) is left to the network untouched.
self.addEventListener('fetch', (event) => {
    if (event.request.mode !== 'navigate' || event.request.method !== 'GET') {
        return;
    }

    event.respondWith(fetch(event.request).catch(() => caches.match(OFFLINE_URL)));
});
