<?php
// $context = Timber::context();
// $context['attributes'] = $attributes;
// Timber::render( array( 'block.twig' ), $context );

?>

<?php if (isset($attributes['image']['url'])) : ?>
  <?php wp_twig_image(array(
    'id' => $attributes['image']['id'],
    'webp' => $attributes['webp_support']
  )); ?>
<?php endif; ?>
