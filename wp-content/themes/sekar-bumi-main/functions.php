<?php
/**
 * sekarbumi functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sekarbumi
 */

if ( ! defined( 'SKBM_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'SKBM_VERSION', '1.0.0' );
}

if ( ! function_exists( 'sekarbumi_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sekarbumi_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on sekarbumi, use a find and replace
		 * to change 'sekarbumi' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'sekarbumi', get_template_directory() . '/languages' );

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

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' 	=> esc_html__( 'Primary', 'sekarbumi' ),
				'side_menu' => esc_html__( 'Side Menu', 'sekarbumi' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
endif;
add_action( 'after_setup_theme', 'sekarbumi_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sekarbumi_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sekarbumi_content_width', 640 );
}
add_action( 'after_setup_theme', 'sekarbumi_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sekarbumi_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'sekarbumi' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'sekarbumi' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'sekarbumi_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sekarbumi_scripts() {
    if( !is_admin() ){
        wp_enqueue_style( 'sekarbumi-uikit', get_template_directory_uri() . '/inc/assets/css/uikit.css', array(), SKBM_VERSION );
        wp_enqueue_style( 'sekarbumi-core', get_template_directory_uri() . '/inc/assets/css/sekarbumi.css', array(), SKBM_VERSION );
    
        wp_enqueue_script( 'sekarbumi-uikit', get_template_directory_uri() . '/inc/assets/js/uikit.min.js', array('jquery'), SKBM_VERSION, false );
        wp_enqueue_script( 'sekarbumi-uikit-icons', get_template_directory_uri() . '/inc/assets/js/uikit-icons.min.js', array('jquery', 'sekarbumi-uikit'), SKBM_VERSION, false );
        wp_enqueue_script( 'sekarbumi-core', get_template_directory_uri() . '/inc/assets/js/sekarbumi.js', array('jquery'), SKBM_VERSION, false );
    
        wp_enqueue_style( 'sekarbumi-style', get_stylesheet_uri(), array(), SKBM_VERSION );
    
        wp_enqueue_script( 'sekarbumi-navigation', get_template_directory_uri() . '/js/navigation.js', array(), SKBM_VERSION, true );
    
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'sekarbumi_scripts' );

// Require Core
require get_template_directory() . '/inc/include.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

if( !function_exists( 'sekarbumi_default_fonts' ) ) {
	function sekarbumi_default_fonts(){
		?>
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
		<?php
	}
	add_action( 'wp_head', 'sekarbumi_default_fonts' );
}

if( !function_exists( 'sekarbumi_default_styles' ) ){
	function sekarbumi_default_styles(){

		$default_styles = '';

		wp_add_inline_style( 'sekarbumi-core', apply_filters( 'sekarbumi_default_styles', $default_styles ) );
	}
	add_action( 'wp_enqueue_scripts', 'sekarbumi_default_styles', 11 );
}

if( !function_exists( 'zod_debug' ) ){
    function zod_debug( $debug, $debug_height='450', $debug_bgcolor='#666', $debug_fontcolor='#fff' ){
      $debug_height = ( $debug_height == '' ) ? '450' : $debug_height;
      $debug_output = '<pre style="font-size:13px; height:'.$debug_height.'px; overflow:scroll-y; background: '.$debug_bgcolor.'; color: '.$debug_fontcolor.';">';
      $debug_output .= '<p> memory usage : '. zod_convert_memory( zod_get_memory_usage() ) .'</p>';
      $debug_output .= print_r( $debug, true );
      $debug_output .= '</pre>';
      printf( "%s", $debug_output );
    }
}

function zod_get_memory_usage( ){
	return memory_get_usage();
}

function zod_convert_memory( $size ){
	$unit = array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}