<?php
  $context = Timber::context();
  $context['attributes'] = $attributes;

  $args = array(
    'post_type' => array( 'post' ),
    'category_name' => $attributes['choose-category'],
    'posts_per_page' => 1,
  );
  $context['posts'] = new Timber\PostQuery($args);
  $context['category'] = get_category_by_slug($attributes['choose-category']);

  Timber::render( array( 'block.twig' ), $context );
?>
