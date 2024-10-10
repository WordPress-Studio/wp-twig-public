<?php
/**
 * Wp Twig Starter theme
 */


$composer_autoload = __DIR__ . '/vendor/autoload.php';


if (file_exists($composer_autoload)) {
  require_once $composer_autoload;
  $timber = new Timber\Timber([
    'debug' => WP_DEBUG,
  ]);

  require_once __DIR__ . '/vendor/redux-framework/redux-core/framework.php';

  require_once __DIR__ . '/inc/options-init.php';
  require_once __DIR__ . '/inc/metabox-init.php';
  require_once __DIR__ . '/inc/post-type/project-post-type.php';
  require_once __DIR__ . '/inc/post-type/project-post-taxonomy.php';
  require_once __DIR__ . '/inc/render-assets.php';
  require_once __DIR__ . '/inc/image-resize.php';
  require_once __DIR__ . '/inc/get-scripts-and-styles.php';


  // Define path and URL to the LZB plugin.
  define('WP_TWIG_LZB_PATH', get_template_directory() . '/vendor/lazy-blocks/');
  define('WP_TWIG_LZB_URL', get_template_directory_uri() . '/vendor/lazy-blocks/');


  // Include the LZB plugin.
  require_once WP_TWIG_LZB_PATH . 'lazy-blocks.php';
  require_once __DIR__ . '/inc/blocks.php';


  // Customize the url setting to fix incorrect asset URLs.
  add_filter('lzb/plugin_url', 'wp_twig_lzb_url');
  function wp_twig_lzb_url($url)
  {
    return WP_TWIG_LZB_URL;
  }
}



/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views', 'templates/components');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


function twig_scripts()
{
  wp_enqueue_script('jquery');
}

function get_theme_opt($keyname)
{
  $theme_options = get_option('theme_option');
  return isset($theme_options[$keyname]) ? $theme_options[$keyname] : null;
}

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class WpTwigStartSite extends Timber\Site
{
  /** Add timber support. */
  public function __construct()
  {
    add_action('after_setup_theme', array($this, 'theme_supports'));
    add_filter('timber/context', array($this, 'add_to_context'));
    add_action('wp_enqueue_scripts', 'twig_scripts');
    add_action('widgets_init', array($this, 'wp_twig_widgets_init'));
    add_filter('block_categories_all', 'wp_twig_block_category', 10, 2);
    add_action('admin_enqueue_scripts', array($this, 'admin_style'));
    add_action('init', array($this, 'register_menus'));

    add_filter('woocommerce_enqueue_styles', array($this, 'woocommerce_dequeue_styles'));
    add_action('wp_enqueue_scripts', array($this, 'blocks_dequeue_styles'));

    $this->globalJavascriptList = array(
      '/static/scripts/load-js.js',
      '/static/scripts/site.js',
    );


    $this->global_styles = array(
      get_template_directory_uri() . '/css/vendor/bootstrap.css',
      get_template_directory_uri() . '/css/main.css',
    );

    if (!function_exists('is_woocommerce_activated')) {
      if (class_exists('woocommerce')) {
        array_push($this->global_styles, WP_CONTENT_URL . '/plugins/woocommerce/assets/css/woocommerce.css');
        array_push($this->global_styles, WP_CONTENT_URL . '/plugins/woocommerce/assets/css/woocommerce-layout.css');
        array_push($this->global_styles, get_template_directory_uri() . '/css/vendor/woocommerce-smallscreen.css');
        array_push($this->global_styles, get_template_directory_uri() . '/css/modules/woocommerce.css');
      }
    }


    // echo '<pre>';
    // print_r($this->global_styles);
    // echo '</pre>';
    parent::__construct();
  }

  public function woocommerce_dequeue_styles($enqueue_styles)
  {
    unset($enqueue_styles['woocommerce-general']);
    unset($enqueue_styles['woocommerce-layout']);
    unset($enqueue_styles['woocommerce-smallscreen']);
    return $enqueue_styles;
  }

  public function blocks_dequeue_styles()
  {
    wp_dequeue_style('wc-block-style');
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wc-blocks-style-css');
  }

  
  // Update CSS within in Admin
  public function admin_style()
  {
    wp_enqueue_style('admin-styles', get_template_directory_uri() . '/css/admin.css');
  }



  public function register_menus()
  {
    $locations = array(
      'header_menu' => __('Header Menu', 'text_domain'),
    );
    register_nav_menus($locations);
  }

  public function wp_twig_widgets_init()
  {

    register_sidebar(
      array(
        'name' => __('Blog Sidebar', 'blog_sidebar'),
        'id' => 'blog_sidebar',
        'description' => __('Add widgets here to appear in your footer.', 'wp_twig'),
        'before_widget' => '<div id="%1$s"  class="widget %2$s card my-4">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title card-header">',
        'after_title' => '</h5>',
      )
    );

    register_sidebar(
      array(
        'name' => __('Page Sidebar', 'page_sidebar'),
        'id' => 'page_sidebar',
        'description' => __('Add widgets here to appear in your footer.', 'wp_twig'),
        'before_widget' => '<div id="%1$s"  class="widget %2$s card my-4">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title card-header">',
        'after_title' => '</h5>',
      )
    );

    register_sidebar(
      array(
        'name' => __('Shop Sidebar', 'shop_sidebar'),
        'id' => 'shop_sidebar',
        'description' => __('Add widgets here to appear in your footer.', 'wp_twig'),
        'before_widget' => '<div id="%1$s"  class="widget %2$s card my-4">',
        'after_widget' => '</div></div>',
        'before_title' => '<h5 class="widget-title card-header">',
        'after_title' => '</h5><div class="card-body">',
      )
    );

  }



  public function add_to_context($context)
  {
    $context['theme_link'] = get_template_directory_uri();
    $context['menu'] = new Timber\Menu('header_menu');
    $context['sidebar'] = Timber::get_widgets('blog_sidebar');
    $context['site_wrapper_class'] = ' container p-0 ';
    $context['site_favicon'] = get_theme_opt('site_favicon');
    $context['site_global_stylesheet_list'] = $this->global_styles;
    $context['footer_copyright_text'] = get_theme_opt('footer-copyright-text');
    $context['service_worker'] = get_theme_opt('service_worker');
    $context['global_js_list'] = $this->globalJavascriptList;
    $context['comment_depth'] = get_option('thread_comments_depth');

    // Page or post meta
    $post_id = get_the_ID();
    $context['page_meta_hide_page_title'] = get_post_meta($post_id, 'page_meta_hide_page_title', true);
    $wrapper_class = get_post_meta($post_id, 'page_meta_wrapper_class', true);
    if ($wrapper_class !== '') {
      $context['site_wrapper_class'] = $wrapper_class;
    }

    $context['display_sidebar'] = get_post_meta($post_id, 'page_meta_display_sidebar', true);
    $sidebar_name = get_post_meta($post_id, 'page_meta_sidebar', true);
    $context['sidebar'] = Timber::get_widgets($sidebar_name);
    $context['animation_support'] = get_post_meta($post_id, 'animation_support', true);


    $context['site'] = $this;
    return $context;
  }

  public function theme_supports()
  {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
      'html5',
      array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
      )
    );

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support(
      'post-formats',
      array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
      )
    );

    add_theme_support('menus');

    add_theme_support('woocommerce');

  }

}

new WpTwigStartSite();

function timber_set_product($post)
{
  global $product;

  if (is_woocommerce()) {
    $product = wc_get_product($post->ID);
  }
}


