gsap.registerPlugin(ScrollTrigger);
gsap.to(".wp-block-lazyblock-get-latest-post", {
  scrollTrigger: ".wp-block-lazyblock-get-latest-post", // start the animation when ".box" enters the viewport (once)
  x: 500
});