1. Link swiper js and swiper CSS from page meta box

  – CSS: https://unpkg.com/swiper@5.4.1/css/swiper.min.css
  – JS: https://unpkg.com/swiper@5.4.1/js/swiper.min.js

2. Create a page specific JS file or edit your page specific js
    - PUT this line of JS code for slider initialization

  ```
  var mySwiper = new Swiper ('.swiper-container', {
  // Optional parameters
    direction: 'horizontal',
    loop: true,

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    preloadImages: false,
    lazy: true
  })

```

Please visit for more config changes and swiper docs: https://swiperjs.com/