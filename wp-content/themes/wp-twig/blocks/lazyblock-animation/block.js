loadjs([theme_root + '/static/scripts/vendor/gsap.min.js', theme_root + '/static/scripts/vendor/scroll-trigger.min.js'], 'gsap');

loadjs.ready('gsap', function () {
  gsap.registerPlugin(ScrollTrigger);
  var els = document.querySelectorAll(".wp-block-lazyblock-animation");
  els.forEach(function(el) {
    var targetAttr = jQuery(el).attr('data-target');
    var target  = el.querySelector(targetAttr);
    var animation  = jQuery(el).attr('data-animation');
    const animObj = Object.assign(JSON.parse(animation), {
      scrollTrigger: el,
    })
    gsap.to(target, animObj);
  })
  
});