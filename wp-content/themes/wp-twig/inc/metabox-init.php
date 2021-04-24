<?php

add_action( 'cmb2_admin_init', 'wp_twig_page_metabox' );

/**
 * Define the metabox and field configurations.
 */
function wp_twig_page_metabox() {

	/**
	 * Initiate the metabox
	 */
	$page_meta = new_cmb2_box( array(
		'id'            => 'page_metabox',
		'title'         => __( 'Page Options', 'wp_twig' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'closed'     => false, // Keep the metabox closed by default
	) );


	$page_meta->add_field( array(
		'name'             => 'Display Sidebar',
		'id'               => 'page_meta_display_sidebar',
		'type'             => 'select',
		'show_option_none' => true,
		'default'          => 'no',
		'options'          => array(
			'yes' => __( 'Yes', 'wp_twig' ),
			'no'   => __( 'No', 'wp_twig' ),
		),
	) );

	$page_meta->add_field( array(
		'name'             => 'Hide Page title',
		'id'               => 'page_meta_hide_page_title',
		'type'             => 'checkbox',
	) );

	$page_meta->add_field( array(
		'name'             => 'Choose sidebar',
		'id'               => 'page_meta_sidebar',
		'type'             => 'select',
		'show_option_none' => true,
		'default'          => 'blog_sidebar',
		'options'          => array(
			'blog_sidebar' => __( 'Blog Sidebar', 'wp_twig' ),
			'page_sidebar'   => __( 'Page Sidebar', 'wp_twig' ),
			'shop_sidebar'   => __( 'Shop Sidebar', 'wp_twig' ),
		),
	) );

	$page_meta->add_field( array(
		'name'             => 'Page wrapper class',
		'id'               => 'page_meta_wrapper_class',
		'type'             => 'text',
		'show_option_none' => true,
		'default'          => 'container p-0',
	) );

	$page_meta_assets = new_cmb2_box( array(
		'id'            => 'page_assets',
		'title'         => __( 'Page Styles and Scripts', 'wp_twig' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'closed'     => false, // Keep the metabox closed by default
	) );

	$page_meta_assets->add_field( array(
		'name'             => 'Page Specific stylesheet',
		'id'               => 'page_meta_stylesheets',
		'description' => 'Example: css/main.css',
		'type'             => 'text',
		'show_option_none' => true,
		'repeatable' => true,
		'default'          => '',
	) );
	$page_meta_assets->add_field( array(
		'name'             => 'Page specific scripts',
		'id'               => 'page_meta_scripts',
		'type'             => 'text',
		'show_option_none' => true,
		'repeatable' => true,
		'default'          => '',
	) );



}