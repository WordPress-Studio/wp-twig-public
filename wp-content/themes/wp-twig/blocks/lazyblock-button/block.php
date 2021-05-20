<?php
$context = Timber::context();
$context['attributes'] = $attributes;
Timber::render( array( 'block.twig' ), $context );
?>
