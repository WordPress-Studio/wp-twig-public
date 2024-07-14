"use strict";
// Async JS load
loadjs(theme_root + "/static/scripts/vendor/lazyload.min.js", "lazyload");
loadjs(theme_root + "/static/scripts/vendor/bowser.min.js", "bowser");

var site = {
  lazyload: function () {
    loadjs.ready("lazyload", function () {
      new LazyLoad({
        elements_selector: ".lazy",
      });
    });
  },
  addBrowserClass: function () {
    loadjs.ready("bowser", function () {
      var browser = bowser.getParser(window.navigator.userAgent);
      var name = browser.getBrowserName().toLowerCase();
      var nameWithVersion =
        name + "-" + browser.getBrowser().version.split(".")[0];
      var className = name + " " + nameWithVersion;
      jQuery("body").addClass(className);
    });
  },
  commentForm: function () {
    if (jQuery(".reply-button").length) {
      jQuery(".reply-button").on("click", function () {
        jQuery(this).next(".comment-form-wrapper").slideToggle();
      });
    }
  },
};

jQuery(document).ready(function ($) {
  site.commentForm();
});

jQuery(window).load(function () {
  site.addBrowserClass();
  setTimeout(function () {
    site.lazyload();
  }, 1000);
});

if ("serviceWorker" in navigator) {
  if (serviceWorkerEnabled === 'activate') {
    navigator.serviceWorker
      .register("/service_worker.js")
      .then((registration) => {
        console.log(
          "Service Worker registered with scope:",
          registration.scope
        );

        registration.onupdatefound = () => {
          const installingWorker = registration.installing;
          installingWorker.onstatechange = () => {
            if (installingWorker.state === "installed") {
              if (navigator.serviceWorker.controller) {
                // New update available
                console.log("New or updated content is available.");
                // Optionally, show a notification to the user to refresh the page
              } else {
                // Content is cached for the first time
                console.log("Content is now available offline!");
              }
            }
          };
        };
      })
      .catch((error) => {
        console.error("Service Worker registration failed:", error);
      });
  } else {
    navigator.serviceWorker
      .getRegistrations()
      .then((registrations) => {
        registrations.forEach((registration) => {
          // Unregister each service worker
          registration.unregister().then((boolean) => {
            if (boolean) {
              console.log("Service Worker uninstall successfully.");
            } else {
              console.log("Service Worker uninstall failed.");
            }
          });
        });
      })
      .catch((error) => {
        console.error("Error fetching service worker registrations:", error);
      });
  }
}

if ("caches" in window) {
  const CACHE_NAME = "857d63b";

  caches
    .keys()
    .then((cacheNames) => {
      console.log("Cache names:", cacheNames);

      // Access the current cache and list its contents
      // Use git commit Id as cache name to identify the version
      // git rev-parse --short HEAD
      const currentCacheName = CACHE_NAME; // Update to match your current cache version
      // if (cacheNames.includes(currentCacheName)) {
      //   caches.open(currentCacheName).then(cache => {
      //     cache.keys().then(requests => {
      //        console.log('Cached files in current cache:', requests.map(req => req.url));
      //     });
      //   });
      // } else {
      //   console.log('Not Matched Cache List', currentCacheName);
      // }

      // Not matched caches delete
      cacheNames.forEach((cacheName) => {
        if (cacheName !== currentCacheName) {
          caches.delete(cacheName);
        }
      });
    })
    .catch((error) => {
      console.error("Error retrieving cache names:", error);
    });
}
