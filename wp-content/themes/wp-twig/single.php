<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::context();
$timber_post     = Timber::query_post();
$context['post'] = $timber_post;
$post_id = get_the_ID();


$context['display_sidebar'] = get_post_meta( $post_id, 'page_meta_display_sidebar', true );
$sidebar_name = get_post_meta( $post_id, 'page_meta_sidebar', true );
$context['sidebar'] = Timber::get_widgets($sidebar_name);
$context['site_wrapper_class'] = get_post_meta($post_id, 'page_meta_wrapper_class', true);
$context['page_specific_style_sheets'] = get_post_meta($post_id, 'page_meta_stylesheets', true);
$context['page_meta_hide_page_title'] = get_post_meta($post_id, 'page_meta_hide_page_title', true);

$js_optimized = is_js_optimized();
$css_optimized = is_css_optimized();

$sheets = renderStyleSheets($timber_post);

if (isset($sheets['block_style_sheets'])) {
  $context['block_style_sheets'] = $sheets['block_style_sheets'];
}
if ($css_optimized && isset($sheets['page_specific_style_sheets'])) {
  $context['page_specific_style_sheets'] = $sheets['page_specific_style_sheets'];
}

$scripts = renderScripts($timber_post);

if (isset($scripts['block_scripts'])) {
  $context['block_scripts'] = $scripts['block_scripts'];
}

if ($js_optimized ) {
  $context['page_specific_scripts'] = $scripts['page_specific_scripts'];
} else {
  $context['page_specific_scripts'] = get_post_meta($post_id, 'page_meta_scripts', true);
}



if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig' ), $context );
}
