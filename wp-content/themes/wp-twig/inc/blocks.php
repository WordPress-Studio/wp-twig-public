<?php
function disable_lazy_block_wrapper( $allow_wrapper, $attributes, $context ) {
    return false;
}

add_filter( 'lazyblock/row/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3 );
add_filter( 'lazyblock/column/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3 );
add_filter( 'lazyblock/image/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3 );
add_filter( 'lazyblock/button/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3 );


function wp_twig_image($options)
{
  $options = array(
    'id' => isset($options['id']) ? $options['id'] : null,
    'width' => isset($options['width']) ? $options['width'] : 0,
    'height' => isset($options['height']) ? $options['height'] : 0,
    'lazy_class' => isset($options['lazy_class']) ? $options['lazy_class'] : 'lazy',
    'lazy' => isset($options['lazy']) ? $options['lazy'] : true,
    'disable_small_image' => isset($options['disable_small_image']) ? $options['disable_small_image'] : 0,
  );

  $image = wp_get_attachment_image_src( $options['id'], 'large');
  $image_small = wp_get_attachment_image_src( $options['id'], 'thumbnail');
  $image_alt = get_post_meta( $options['id'], '_wp_attachment_image_alt', TRUE);

  $width = $image[1] > 1 ? $image[1] : $options['width'];
  $height = $image[1] > 1 ? $image[1] : $options['height'];

  $lazy_attribute = '" data-src="' . esc_url($image[0]);

  if ($options['lazy'] == 0) {
    $lazy_attribute = '" src="' . esc_url($image[0]);
  }

  $src = '';
  if ($options['lazy'] && $options['disable_small_image'] == 0) {
    $src = '" src="' . esc_url($image_small[0]) . '"';
  }

  echo '<img class="' . $options['lazy_class'] . $lazy_attribute . '"'.  $src . ' width="' . $width . '" height="' . $height . '" alt="' .$image_alt . '">';
}
