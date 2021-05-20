<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

$project_args = array(
	'post_type'              => array( 'projects' ),
	'nopaging'               => true,
	'posts_per_page'         => '4',
);
$post_id = get_the_ID();
$context['projects'] = new Timber\PostQuery($project_args);;
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


Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
