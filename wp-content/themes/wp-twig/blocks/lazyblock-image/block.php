<?php if (isset($attributes['image']['url'])) : ?>
  <?php wp_twig_image(array(
    'id' => $attributes['image']['id']
  )); ?>
<?php endif; ?>
