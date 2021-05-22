loadjs(theme_root + '/blocks/lazyblock-slider/swiper.js', 'swiper');

loadjs.ready('swiper', function () {
  var autoSlide = +jQuery('.swiper-container').attr('data-auto-slide');
  const swiper = new Swiper('.swiper-container', {
    direction: 'horizontal',
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    autoplay: autoSlide ? {
      delay: jQuery('.swiper-container').attr('data-slide-change-interval'),
      disableOnInteraction: false
    } : false,
    autoHeight: true,
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
  });
});
