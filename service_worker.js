
// Define the cache name
const CACHE_NAME = 'my-cache-v6';

// Define the files to cache
const FILES_TO_CACHE = [
];

// Install event - cache the files
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Service Worker] Pre-caching files');
        return cache.addAll(FILES_TO_CACHE);
      })
  );
});

// Fetch event - serve cached files or fetch from network if not cached
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response; // Serve from cache if found
        }
        return fetch(event.request); // Let network handle uncached requests
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];

  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            // Delete old caches
            return caches.delete(cacheName);
          } else {
            console.log('service worker cache:', cacheName);
          }
        })
      );
    }).then(() => {
      // Ensure the service worker is now active and controlling the page
      return self.clients.claim();
    })
  );
});
