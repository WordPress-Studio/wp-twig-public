<?php

/**
 * ReduxFramework Config for WP TWIG
 * For full documentation, please visit: https://devs.redux.io/
 */


// This is your option name where all the Redux data is stored.
$opt_name = "theme_option";

// This line is only for altering the demo. Can be easily removed.
$opt_name = apply_filters('theme_option/opt_name', $opt_name);


/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://devs.redux.io/configuration/
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
  // TYPICAL -> Change these values as you need/desire
  'opt_name'             => $opt_name,
  // This is where your data is stored in the database and also becomes your global variable name.
  'display_name'         => $theme->get('Name'),
  // Name that appears at the top of your panel
  'display_version'      => $theme->get('Version'),
  // Version that appears at the top of your panel
  'menu_type'            => 'submenu',
  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
  'allow_sub_menu'       => true,
  // Show the sections below the admin menu item or not
  'menu_title'           => __('Theme Option', 'redux-framework-demo'),
  'page_title'           => __('Theme Option', 'redux-framework-demo'),
  // You will need to generate a Google API key to use this feature.
  // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
  'google_api_key'       => '',
  // Set it you want google fonts to update weekly. A google_api_key value is required.
  'google_update_weekly' => false,
  // Must be defined to add google fonts to the typography module
  'async_typography'     => false,
  // Use a asynchronous font on the front end or font string
  //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
  'admin_bar'            => true,
  // Show the panel pages on the admin bar
  'admin_bar_icon'       => 'dashicons-portfolio',
  // Choose an icon for the admin bar menu
  'admin_bar_priority'   => 50,
  // Choose an priority for the admin bar menu
  'global_variable'      => 'theme_option',
  // Set a different name for your global variable other than the opt_name
  'dev_mode'             => false,
  // Show the time the page took to load, etc
  'update_notice'        => false,
  // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
  'customizer'           => true,

  // OPTIONAL -> Give you extra features
  'page_priority'        => null,
  // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
  'page_parent'          => 'themes.php',
  // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
  'page_permissions'     => 'manage_options',
  // Permissions needed to access the options panel.
  'menu_icon'            => '',
  // Specify a custom URL to an icon
  'last_tab'             => '',
  // Force your panel to always open to a specific tab (by id)
  'page_icon'            => 'icon-themes',
  // Icon displayed in the admin panel next to your menu_title
  'page_slug'            => 'theme_option',
  // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
  'save_defaults'        => true,
  // On load save the defaults to DB before user clicks save or not
  'default_show'         => false,
  // If true, shows the default value next to each field that is not the default value.
  'default_mark'         => '',
  // What to print by the field's title if the value shown is default. Suggested: *
  'show_import_export'   => true,
  // Shows the Import/Export panel when not used as a field.

  // CAREFUL -> These options are for advanced use only
  'transient_time'       => 60 * MINUTE_IN_SECONDS,
  'output'               => true,
  // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
  'output_tag'           => true,
  'footer_credit'     => '',

  // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
  'database'             => '',
  // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
  'use_cdn'              => true,
  // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

);



// Panel Intro text -> before the form
if (!isset($args['global_variable']) || $args['global_variable'] !== false) {
  if (!empty($args['global_variable'])) {
    $v = $args['global_variable'];
  } else {
    $v = str_replace('-', '_', $args['opt_name']);
  }
}

Redux::setArgs($opt_name, $args);
Redux::set_extensions($opt_name, __DIR__ . '/redux-extensions/clear_cache/extension_clear_cache.php');

/*
* ---> END ARGUMENTS
*/


Redux::setSection($opt_name, array(
  'title'  => esc_html__('Header', 'redux-framework-demo'),
  'id'     => 'header-section',
  'icon'   => 'el el-adjust-alt',
  'fields' => array(
    array(
      'id'       => 'site_favicon',
      'type'     => 'text',
      'title'    => esc_html__('Site Favicon', 'redux-framework-demo'),
      'default' => esc_html__('' . get_stylesheet_directory_uri() . '/static/images/favicon.png', 'redux-framework-demo'),
    ),
  )
));



Redux::setSection($opt_name, array(
  'title'  => esc_html__('Footer', 'redux-framework-demo'),
  'id'     => 'footer-section',
  'icon'   => 'el el-adjust-alt',
  'fields' => array(
    array(
      'id'       => 'footer-copyright-text',
      'type'     => 'text',
      'title'    => esc_html__('Footer copyright text', 'redux-framework-demo'),
      'default' => esc_html__('© ' . date("Y") . ' Itobuz Technologies All Rights Reserved', 'redux-framework-demo'),
    )
  )
));

Redux::setSection($opt_name, array(
  'title'  => esc_html__('Optimization', 'redux-framework-demo'),
  'id'     => 'optimization-section',
  'icon'   => 'el el-adjust-alt',
  'fields' => array(

    array(
      'id'   => 'info_normal',
      'type' => 'info',
      'desc' => __('Image Optimization', 'redux-framework-demo')
    ),

    array(
      'id'       => 'service_worker',
      'type'     => 'checkbox',
      'title'    => esc_html__('Service Worker Activate', 'redux-framework-demo'),
      'desc' => esc_html__('Service Worker Can speed up loading speed for end User', 'redux-framework-demo'),
      'default' => false
    ),

    array(
      'id'       => 'image_jpeg_max_quality',
      'type'     => 'text',
      'title'    => esc_html__('JPEG max quality', 'redux-framework-demo'),
      'default' => 85,
      'validate' => array('numeric', 'not_empty')
    ),
    array(
      'id'       => 'image_jpeg_default_quality',
      'type'     => 'text',
      'title'    => esc_html__('JPEG Default quality', 'redux-framework-demo'),
      'default' => 75,
      'validate' => array('numeric', 'not_empty')
    ),

    array(
      'id'       => 'image_png_max_quality',
      'type'     => 'text',
      'title'    => esc_html__('PNG max quality', 'redux-framework-demo'),
      'default' => 85,
      'validate' => array('numeric', 'not_empty')
    ),
    array(
      'id'       => 'image_png_default_quality',
      'type'     => 'text',
      'title'    => esc_html__('PNG Default quality', 'redux-framework-demo'),
      'default' => 75,
      'validate' => array('numeric', 'not_empty')
    ),
    array(
      'id'       => 'image_debug_mode',
      'type'     => 'checkbox',
      'title'    => esc_html__('Image optimization debug mode', 'redux-framework-demo'),
      'desc' => esc_html__('Always create new image and replace existing on the fly. If this is enabled', 'redux-framework-demo'),
      'default' => false
    ),

    array(
      'id'       => 'clear_cache',
      'type'     => 'clear_cache',
      'title'    => esc_html__('Clear Cache', 'redux-framework-demo'),
      'default' => false,
    ),
  )
));



if (file_exists(dirname(__FILE__) . '/../README.md')) {
  $section = array(
    'icon'   => 'el el-list-alt',
    'title'  => __('Documentation', 'redux-framework-demo'),
    'fields' => array(
      array(
        'id'       => '17',
        'type'     => 'raw',
        'markdown' => true,
        'content_path' => dirname(__FILE__) . '/../README.md', // FULL PATH, not relative please
        //'content' => 'Raw content here',
      ),
    ),
  );
  Redux::setSection($opt_name, $section);
}
            /*
            * <--- END SECTIONS
            */
