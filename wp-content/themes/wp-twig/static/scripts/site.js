"use strict";
// Async JS load
loadjs(theme_root + '/static/scripts/vendor/lazyload.min.js', 'lazyload');
loadjs(theme_root + '/static/scripts/vendor/bowser.min.js', 'bowser');

var site = {
  lazyload: function() {
    loadjs.ready('lazyload', function () {
      new LazyLoad({
        elements_selector: '.lazy'
      });
    });
  },
  addBrowserClass: function() {
    loadjs.ready('bowser', function () {
      var browser = bowser.getParser(window.navigator.userAgent);
      var name = browser.getBrowserName().toLowerCase();
      var nameWithVersion = name + '-' + browser.getBrowser().version.split('.')[0];
      var className = name + ' ' + nameWithVersion;
      jQuery('body').addClass( className );
    });
  }
}

jQuery(document).ready(function ($) {
  console.log('jQuery Ready!');
});

jQuery(window).load(function () {
  site.addBrowserClass();
  setTimeout(function () {
    site.lazyload();
  }, 3000);
});
