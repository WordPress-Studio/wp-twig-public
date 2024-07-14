// Define the cache name
// Use Git commit id: git rev-parse --short HEAD
const CACHE_NAME = "857d63b";

// Define file extensions to cache
const EXTENSIONS_TO_CACHE = [
  ".js",
  ".css",
  ".png",
  ".jpg",
  ".jpeg",
  ".gif",
  ".mp4",
];

// Define files to exclude from cache
const EXCLUDED_FILES = ["/wp-content/themes/wp-twig/static/scripts/site.js"];

// Define files to preload
const FILES_TO_PRELOAD = [
  "/wp-content/themes/wp-twig/static/images/favicon.png",
];

// Function to determine if a URL should be cached
function shouldCache(url) {
  const isExcluded = EXCLUDED_FILES.some((excludedUrl) =>
    url.includes(excludedUrl)
  );
  if (isExcluded) {
    return false;
  }
  return EXTENSIONS_TO_CACHE.some((extension) => url.endsWith(extension));
}

// Install event - cache the files
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log("[Service Worker] Pre-caching files");

      // Preload specified files
      const preloadPromises = FILES_TO_PRELOAD.map((file) => {
        return cache.add(new Request(file, { cache: "reload" }));
      });

      // Precache files based on extensions
      return Promise.all(preloadPromises).then(() => {
        return cache.addAll([
          // Optionally add other files to cache here if needed
        ]);
      });
    })
  );
});

// Fetch event - serve cached files or fetch from network if not cached
self.addEventListener("fetch", (event) => {
  const requestUrl = new URL(event.request.url);

  // Only handle requests to the same origin
  if (
    requestUrl.origin === location.origin &&
    shouldCache(requestUrl.pathname)
  ) {
    event.respondWith(
      caches
        .match(event.request)
        .then((response) => {
          if (response) {
            return response; // Serve from cache if found
          }
          return fetch(event.request).then((networkResponse) => {
            if (networkResponse.status === 200) {
              // Optionally, cache the response here
              return caches.open(CACHE_NAME).then((cache) => {
                cache.put(event.request, networkResponse.clone());
                return networkResponse;
              });
            } else {
              // Optionally, handle other response codes here
              return networkResponse;
            }
          });
        })
        .catch(() => {
          // Optionally handle fetch errors (e.g., offline)
          console.error("[Service Worker] Fetch error:", event.request.url);
        })
    );
  } else {
    // Let the network handle requests for non-cached files
    event.respondWith(fetch(event.request));
  }
});

// Activate event - clean up old caches
self.addEventListener("activate", (event) => {
  const cacheWhitelist = [CACHE_NAME];

  event.waitUntil(
    caches
      .keys()
      .then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheWhitelist.indexOf(cacheName) === -1) {
              // Delete old caches
              console.log("[Service Worker] Deleting old cache:", cacheName);
              return caches.delete(cacheName);
            } else {
              console.log("[Service Worker] Keeping cache:", cacheName);
            }
          })
        );
      })
      .then(() => {
        // Ensure the service worker is now active and controlling the page
        return self.clients.claim();
      })
  );
});
