<?php
// Webp 
use WebPConvert\WebPConvert;

function disable_lazy_block_wrapper()
{
  return false;
}

add_filter('lazyblock/row/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3);
add_filter('lazyblock/column/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3);
add_filter('lazyblock/image/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3);
add_filter('lazyblock/button/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3);
add_filter('lazyblock/animation/allow_wrapper', 'disable_lazy_block_wrapper', 10, 3);

function getWebpURL($id)
{
  $file = get_post_meta($id, '_wp_attached_file', true);
  $file = $file . '.webp';

  // If the file is relative, prepend upload dir.
  if ($file && 0 !== strpos($file, '/') && !preg_match('|^.:\\\|', $file)) {
    $uploads = wp_get_upload_dir();

    if (false === $uploads['error']) {
      $file = $uploads['baseurl'] . "/$file";
    }
  }

  return $file;
}

function wp_twig_image($options)
{
  $options = array(
    'id' => isset($options['id']) ? $options['id'] : null,
    'width' => isset($options['width']) ? $options['width'] : 0,
    'height' => isset($options['height']) ? $options['height'] : 0,
    'lazy_class' => isset($options['lazy_class']) ? $options['lazy_class'] : 'lazy',
    'lazy' => isset($options['lazy']) ? $options['lazy'] : true,
    'disable_small_image' => isset($options['disable_small_image']) ? $options['disable_small_image'] : 0,
    'webp' => isset($options['webp']) ? $options['webp'] : false,
  );

  $image = wp_get_attachment_image_src($options['id'], 'full');
  $image_small = aq_resize($image[0], 100);
  $image_alt = get_post_meta($options['id'], '_wp_attachment_image_alt', TRUE);

  $width = $image[1] > 1 ? $image[1] : $options['width'];
  $height = $image[1] > 1 ? $image[2] : $options['height'];

  $lazy_attribute = '" data-src="' . esc_url($image[0]);

  if ($options['lazy'] == 0) {
    $lazy_attribute = '" src="' . esc_url($image[0]);
  }

  $src = '';
  if ($options['lazy'] && $options['disable_small_image'] == 0) {
    $src = '" src="' . $image_small . '"';
  }

  $img =  '<img class="' . $options['lazy_class'] . $lazy_attribute . '"' .  $src . ' width="' . $width . '" height="' . $height . '" alt="' . $image_alt . '">';


  if ($options['webp']) {
    $source = get_attached_file($options['id']);
    $destination = $source . '.webp';
    $img_options = [
      'converters' => [
        'cwebp', 'vips', 'imagick', 'gmagick', 'imagemagick', 'graphicsmagick', 'wpc', 'ewww', 'gd'
      ],
      'png' => [
        'encoding' => 'auto', 
        'near-lossless' => get_theme_opt('image_png_max_quality') + 0,
        'quality' => get_theme_opt('image_png_max_quality') + 0,
        'sharp-yuv' => true,
      ],
      'jpeg' => [
        'encoding' => 'auto',
        'quality' => 'auto',
        'max-quality' => get_theme_opt('image_jpeg_max_quality') + 0,
        'default-quality' => get_theme_opt('image_jpeg_default_quality') + 0, 
        'sharp-yuv' => true,
      ]
    ];

    if (!file_exists($destination) || get_theme_opt('image_debug_mode')) {
      WebPConvert::convert($source, $destination, $img_options);
    }

    $webpImage = '';
    if (file_exists($destination)) {
      $webpImage = '<source " data-srcset="' . getWebpURL($options['id']) . '">';
    }
    

    echo '
    <picture>
      '. $webpImage .'' . $img . '
    </picture>';
  } else {
    echo $img;
  }
}


add_filter('timber/twig', 'add_to_twig');

function jsonDecode($obj)
{
  echo json_encode($obj);
}

/**
 * My custom Twig functionality.
 *
 * @param \Twig\Environment $twig
 * @return \Twig\Environment
 */
function add_to_twig($twig)
{
  // Adding a function.
  $twig->addFunction(new Timber\Twig_Function('jsonDecode', 'jsonDecode'));

  return $twig;
}

/**
 * Summary of wp_twig_block_category
 * @param mixed $categories
 * @param mixed $post
 * @return mixed
 */
function wp_twig_block_category($categories, $post)
{
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'wp_twig_blocks',
        'title' => __('Wp Twig Blocks', 'wp_twig_blocks'),
      ),
    )
  );
}