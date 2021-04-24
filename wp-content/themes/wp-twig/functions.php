<?php
/**
 * Wp Twig Starter theme
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

 /**
 * Get the bootstrap!
 * (Update path to use cmb2 or CMB2, depending on the name of the folder.
 * Case-sensitive is important on some systems.)
 */



/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';

// Minify library added
use MatthiasMullie\Minify;

if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();


  // TODO: Set redux URL
  // wp-twig/vendor/redux-framework/redux-core/class-redux-core.php | line 234
  // self::$url       = get_template_directory_uri() . '/vendor/redux-framework/redux-core/';

  // Update scripts and styles path
  // wp-twig/vendor/redux-framework/redux-core/inc/extensions/import_export/import_export/class-redux-import-export.php | line 189
  // Redux_Core::$url . '/inc/extensions/import_export/import_export/' . 'redux-import-export' . Redux_Functions::is_min() . '.js',

	require_once __DIR__ . '/vendor/redux-framework/redux-core/framework.php';

	require_once __DIR__ . '/inc/options-init.php';
	require_once __DIR__ . '/inc/metabox-init.php';
	require_once __DIR__ . '/inc/post-type/project-post-type.php';
	require_once __DIR__ . '/inc/post-type/project-post-taxonomy.php';
	require_once __DIR__ . '/inc/blocks.php';
	require_once __DIR__ . '/inc/render-assets.php';

  $admin_path = str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() );
  require_once ( $admin_path . '/includes/class-wp-filesystem-base.php' );
  require_once ( $admin_path . '/includes/class-wp-filesystem-direct.php' );

	// Define path and URL to the LZB plugin.
	define( 'WP_TWIG_LZB_PATH', get_stylesheet_directory() . '/vendor/lazy-blocks/' );
	define( 'WP_TWIG_LZB_URL', get_stylesheet_directory_uri() . '/vendor/lazy-blocks/' );

	// Include the LZB plugin.
	require_once WP_TWIG_LZB_PATH . 'lazy-blocks.php';

	// Customize the url setting to fix incorrect asset URLs.
	add_filter( 'lzb/plugin_url', 'wp_twig_lzb_url' );
	function wp_twig_lzb_url( $url ) {
			return WP_TWIG_LZB_URL;
	}
}





function wp_twig_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'wp_twig_blocks',
				'title' => __( 'Wp Twig Blocks', 'wp_twig_blocks' ),
			),
		)
	);
}



/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views', 'templates/components' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


function twig_scripts() {
	wp_enqueue_script('jquery');
}

function get_theme_opt($keyname) {
	$theme_options = get_option( 'theme_option' );
	return isset($theme_options[$keyname]) ? $theme_options[$keyname] : null;
}

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class WpTwigStartSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_action( 'init', array( $this, 'create_optimize_dir' ) );
		add_action( 'wp_enqueue_scripts', 'twig_scripts' );
		add_action( 'widgets_init', array( $this, 'wp_twig_widgets_init' ) );
		add_filter( 'block_categories', 'wp_twig_block_category', 10, 2);
		add_action('admin_enqueue_scripts', array( $this, 'admin_style' ));
		add_action( 'init', array( $this, 'register_menus' ) );
		add_action( 'template_redirect', array( $this, 'minify_global_js' )  );
    add_action( 'template_redirect', array( $this, 'minify_global_css' )  );
    add_action( 'admin_footer', array($this, 'set_nonce') );
    add_action( 'wp_ajax_clear_cache', array($this, 'clear_cache_callback') );

    add_filter( 'woocommerce_enqueue_styles', array($this, 'woocommerce_dequeue_styles') );
    add_action( 'wp_enqueue_scripts', array($this,  'blocks_dequeue_styles'));

    $WP_INCLUDE_DIR = preg_replace('/wp-content$/', 'wp-includes', WP_CONTENT_DIR);
    if (is_css_optimized()) {
      $this->global_styles = array(
        $WP_INCLUDE_DIR . '/css/dist/block-library/style.min.css',
        get_stylesheet_directory() .'/css/vendor/bootstrap.css',
        get_stylesheet_directory() .'/css/main.css',
      );

      if ( ! function_exists( 'is_woocommerce_activated' ) ) {
        if ( class_exists( 'woocommerce' ) ) {
          array_push($this->global_styles, WP_CONTENT_DIR . '/plugins/woocommerce/assets/css/woocommerce.css');
          array_push($this->global_styles, WP_CONTENT_DIR . '/plugins/woocommerce/assets/css/woocommerce-layout.css');
          array_push($this->global_styles, get_template_directory() . '/css/vendor/woocommerce-smallscreen.css');
          array_push($this->global_styles, WP_CONTENT_DIR . '/plugins/woocommerce/packages/woocommerce-blocks/build/style.css');
        }
      }
    } else {
      $this->global_styles = array(
        get_stylesheet_directory_uri() .'/css/vendor/bootstrap.css',
        get_stylesheet_directory_uri() .'/css/main.css',
      );
    }




    // echo '<pre>';
    // print_r($this->global_styles);
    // echo '</pre>';
		parent::__construct();
	}

  public function woocommerce_dequeue_styles( $enqueue_styles ) {
    unset( $enqueue_styles['woocommerce-general'] ); // woocommerce/assets/css/woocommerce.css
    unset( $enqueue_styles['woocommerce-layout'] ); // woocommerce/assets/css/woocommerce-layout.css
    unset( $enqueue_styles['woocommerce-smallscreen'] );
    return $enqueue_styles;
  }

  public function blocks_dequeue_styles() {
    wp_dequeue_style( 'wc-block-style' ); // woocommerce/packages/woocommerce-blocks/build/style.css
    wp_dequeue_style( 'wp-block-library' ); // wp-includes/css/dist/block-library/style.min.css
  }

  public function clear_cache_callback() {
    try {
      check_ajax_referer( NONCE_SALT, 'security' );
      $fileSystemDirect = new WP_Filesystem_Direct(false);
      $base_dir = WP_CONTENT_DIR . '/wp_twig_minify';
      $fileSystemDirect->rmdir($base_dir, true);
      echo 'Cache clear successfully';
    } catch (Exception $e) {
      echo 'Message: ' .$e->getMessage();
    }
    die();
  }

  public function set_nonce() {
    $ajax_nonce = wp_create_nonce( NONCE_SALT );
    echo '<script>var wp_ajax_nonce="'. $ajax_nonce .'"</script>';
  }

	// Update CSS within in Admin
	public function admin_style() {
		wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/admin.css');
	}



	public function create_optimize_dir() {

    $base_dir = WP_CONTENT_DIR . '/wp_twig_minify';
    $css_dir = WP_CONTENT_DIR . '/wp_twig_minify/css';
    $js_dir = WP_CONTENT_DIR . '/wp_twig_minify/js';

    if (! is_dir($css_dir)) {
      mkdir( $base_dir, 0700 );
      mkdir( $css_dir, 0700 );
      mkdir( $js_dir, 0700 );
    }

	}

	public function register_menus() {
		$locations = array(
			'header_menu' => __( 'Header Menu', 'text_domain' ),
		);
		register_nav_menus( $locations );
	}


	/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
public function wp_twig_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'blog_sidebar' ),
			'id'            => 'blog_sidebar',
			'description'   => __( 'Add widgets here to appear in your footer.', 'wp_twig' ),
			'before_widget' => '<div id="%1$s"  class="widget %2$s card my-4">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h5 class="widget-title card-header">',
			'after_title'   => '</h5><div class="card-body">',
		));

	register_sidebar(
		array(
			'name'          => __( 'Page Sidebar', 'page_sidebar' ),
			'id'            => 'page_sidebar',
			'description'   => __( 'Add widgets here to appear in your footer.', 'wp_twig' ),
			'before_widget' => '<div id="%1$s"  class="widget %2$s card my-4">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h5 class="widget-title card-header">',
			'after_title'   => '</h5><div class="card-body">',
		));

		register_sidebar(
			array(
				'name'          => __( 'Shop Sidebar', 'shop_sidebar' ),
				'id'            => 'shop_sidebar',
				'description'   => __( 'Add widgets here to appear in your footer.', 'wp_twig' ),
				'before_widget' => '<div id="%1$s"  class="widget %2$s card my-4">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<h5 class="widget-title card-header">',
				'after_title'   => '</h5><div class="card-body">',
			));

}



	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['menu']  = new Timber\Menu('header_menu');
		$context['sidebar'] = Timber::get_widgets('blog_sidebar');
		$context['site_wrapper_class'] = ' container p-0 ';
		$context['site_favicon'] = get_theme_opt('site_favicon');
		$context['site_global_stylesheet_list'] = $this->getGlobalStyle();
		$context['footer_copyright_text'] = get_theme_opt('footer-copyright-text');
		$context['rocket_loader'] = get_theme_opt('rocket-loader');

		$context['global_css'] = WP_CONTENT_URL . '/wp_twig_minify/css/global.css';
		$context['global_js'] = WP_CONTENT_URL . '/wp_twig_minify/js/global.js';
    $context['global_js_list'] =  $this->getGlobalJS();
		$context['css_optimize'] = is_css_optimized('css-optimize');
    $context['js_optimize'] = is_js_optimized();

		$context['site']  = $this;
		return $context;
	}



	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

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

		add_theme_support( 'menus' );

		add_theme_support( 'woocommerce' );

	}

	public function getGlobalStyle() {
    return $this->global_styles;
  }

  public function getGlobalJS() {
    return array(
      '/static/scripts/load-js.js',
      '/static/scripts/vendor/svgxuse.min.js',
      '/static/scripts/vendor/webfont.js',
      '/static/scripts/site.js',
    );
  }

	public function minify_global_css() {
    $css_optimized = is_css_optimized();
		$stylesheets = $this->getGlobalStyle();


		if ($css_optimized) {
			$minifier = new Minify\CSS();
			if ($stylesheets) {
				foreach ($stylesheets as $sheet) {
					$minifier->add($sheet);
				}

				$cssPath = '/wp_twig_minify/css/global.css';
				$minifiedPath = WP_CONTENT_DIR . $cssPath;

        if (isDebugModeEnabled() || !file_exists($minifiedPath)) {
          $minifier->minify($minifiedPath);
        }
			}
		}
  }

  public function minify_global_js() {
    $js_optimized = get_theme_opt('js-optimize');
    $global_js = $this->getGlobalJS();

		if ($js_optimized && $global_js) {
			$global_page_minifier = new Minify\JS();
			foreach ($global_js as $js) {
				$global_page_minifier->add(get_stylesheet_directory() . $js);
			}

			$jsPath = '/wp_twig_minify/js/global.js';
			$cssURL = WP_CONTENT_URL . $jsPath;
			$minifiedPath = WP_CONTENT_DIR . $jsPath;
      if (isDebugModeEnabled() || !file_exists($minifiedPath)) {
        $global_page_minifier->minify($minifiedPath);
      }
		}
	}



}

new WpTwigStartSite();

function timber_set_product( $post ) {
	global $product;

	if ( is_woocommerce() ) {
			$product = wc_get_product( $post->ID );
	}
}

function preview_image_lazyblock_handlebars_helper ( $handlebars ) {
	$handlebars->registerHelper( 'preview_image_helper', function( $val ) {
		return wp_get_attachment_image($val, 'full');
	} );
}
add_action( 'lzb/handlebars/object', 'preview_image_lazyblock_handlebars_helper' );

function minifyCSS($stylesheets, $file_name) {
  $minifier = new Minify\CSS();
  if ($stylesheets) {
    foreach ($stylesheets as $sheet) {
      if (file_exists(get_stylesheet_directory() . $sheet)) {
        $minifier->add(get_stylesheet_directory() . $sheet);
      }
    }

    $cssPath = '/wp_twig_minify/css/' . $file_name . '.css';
    $cssURL = WP_CONTENT_URL . $cssPath;
    $minifiedPath = WP_CONTENT_DIR . $cssPath;
    if (isDebugModeEnabled() || !file_exists($minifiedPath)) {
      $minifier->minify($minifiedPath);
    }
    return $cssURL;
  }
}

function minifyJS($scripts, $file_name) {

  $js_page_minifier = new Minify\JS();
  foreach ($scripts as $js) {
    $js_page_minifier->add(get_stylesheet_directory() . $js);
  }

  $jsPath = '/wp_twig_minify/js/' . $file_name . '.js';
  $jsURL = WP_CONTENT_URL . $jsPath;
  $minifiedPath = WP_CONTENT_DIR . $jsPath;
  if (isDebugModeEnabled() || !file_exists($minifiedPath)) {
    $js_page_minifier->minify($minifiedPath);
  }
  return $jsURL;
}

function is_js_optimized() {
  if (get_theme_opt('js-optimize')) {
    return true;
  }

  return false;
}

function is_css_optimized() {
  if (get_theme_opt('css-optimize')) {
    return true;
  }

  return false;
}

function isDebugModeEnabled() {
  return WP_DEBUG;
}
