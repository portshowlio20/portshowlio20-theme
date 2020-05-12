<?php
/**
 * portshowlio20 functions and definitions
 *
 * 🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳
 * 🚨🚨 NOTE: everything is default execpt for stuff on line 159 and on! 🚨🚨
 * 🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠🤠
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package portshowlio20
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'portshowlio20_setup' ) ) :
	function portshowlio20_setup() {
		load_theme_textdomain( 'portshowlio20', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'portshowlio20' ),
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

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'portshowlio20_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'portshowlio20_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function portshowlio20_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'portshowlio20_content_width', 640 );
}
add_action( 'after_setup_theme', 'portshowlio20_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function portshowlio20_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'portshowlio20' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'portshowlio20' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'portshowlio20_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function portshowlio20_scripts() {
	wp_enqueue_style( 'portshowlio20-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'portshowlio20-style', 'rtl', 'replace' );
	wp_enqueue_script( 'portshowlio20-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'portshowlio20-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), _S_VERSION, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'portshowlio20_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Limit options for users with "author" role:
 * 1. Redirect most admin pages to Projects admin page
 * 2. Remove admin panel menu items (on the left)
 * 3. Remove admin bar menu items (on the top)
 * 4. Remove unecessary input fields on Profile page
 */
$user = wp_get_current_user();
if ( in_array( 'author', (array) $user->roles ) ) {

  // 1.
  add_action('admin_init', 'portshowlio20_admin_pages_redirect');
  function portshowlio20_admin_pages_redirect() {
    global $pagenow;
    $admin_pages = array(
      'about.php',
      'index.php',
      'upload.php',
      // 'edit.php', this breaks things
      'edit.php?post_type=page',
      'edit-tags.php',
      'edit-tags.php',
      'edit-comments.php',
      'link-manager.php',
      'tools.php',
      'options-writing.php',
      'options-reading.php',
      'options-discussion.php',
      'options-media.php',
      'options-privacy.php',
      'options-permalink.php',
    );

    if(in_array($pagenow, $admin_pages)){
      wp_redirect( 'edit.php?post_type=projects' ); exit;
    }
  }

  // 2.
  add_action( 'admin_menu', 'portshowlio20_remove_menu_items', 99 );
  function portshowlio20_remove_menu_items() {
    remove_menu_page( 'index.php' ); // Dashboard
    remove_menu_page( 'upload.php' ); // Dashboard
    remove_menu_page( 'edit.php' ); // Posts
    remove_menu_page( 'link-manager.php' ); // Links
    remove_menu_page( 'edit-comments.php' ); // Comments
    remove_menu_page( 'edit.php?post_type=page' ); // Pages
    remove_menu_page( 'tools.php' ); // Tools
  }

  // 3.
  add_action('admin_bar_menu', 'portshowlio20_remove_from_admin_bar', 999);
  function portshowlio20_remove_from_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('customize');
  }

  // 4.
  add_action( 'admin_footer', 'portshowlio20_remove_profile_fields' );
  function portshowlio20_remove_profile_fields()
  {
    ?>
    <script>
      jQuery(document).ready( function($) {
        $('#your-profile').children('h2').remove(); // All headers
        $('input#rich_editing').closest('table').remove() // Personal Options content
        $('input#user_login').closest('tr').remove(); // Username
        $('input#nickname').closest('tr').remove(); // Nickname (required)
        $('select#display_name').closest('tr').remove(); // Display my name as...
        $('input#url').closest('tr').remove(); // Website (will handle with ACF)
        $('textarea#description').closest('table').remove(); // About

        // INSERT header for ACF section
        $('<h2>Student Info</h2>').insertBefore( $('.acf-field').closest('table') );

        // Wrap sections TBD...
        $('#your-profile').children('table:lt(3)').wrapAll( "<div class='new'></div>" );;
      });
    </script>
    <?php
  }
}

/**
 * Add jQuery script to "projects" custom post type page in order to:
 * 1. limit the number of categories users can add
 */
add_action('admin_enqueue_scripts', 'my_acf_extension_enqueue');
function my_acf_extension_enqueue($hook) {
  # Not our screen, bail out
  if( 'post-new.php' !== $hook )
  return;

  # Not our post type, bail out
  global $typenow;
  if( 'projects' !== $typenow )
  return;

  // 1.
  $handle = 'acfMultiSelectLimit';
  $src = get_stylesheet_directory_uri() . '/js/acfMultiSelectLimit.js';
  $deps = array('jquery', 'acf-input');

  wp_register_script($handle, $src, $deps, false, true);
  wp_enqueue_script($handle, $src, $deps, false, true);
}

/**
 * Validate the categories input incase they add a custom category at the end!
 */
add_filter('acf/validate_value/name=project_type', 'my_acf_validate_value', 10, 4);
function my_acf_validate_value( $valid, $value, $field, $input_name ) {
    // Bail early if value is already invalid.
    if( $valid !== true ) {
        return $valid;
    }
    // Prevent value from saving if it contains the companies old name.
    if( is_array($value) && count($value) >4 !== false ) {
        return __( "You may only pick up to 4 types." );
    }
    return $valid;
}

/**
 * Give authors power to create categories
 */
add_action( 'admin_init', 'add_manage_cat_to_author_role', 10, 0 );
function add_manage_cat_to_author_role() {
  if ( ! current_user_can( 'author' ) )
      return;

  // here you should check if the role already has_cap already and if so, abort/return;
  if ( current_user_can( 'author' ) )
  {
      $GLOBALS['wp_roles']->add_cap( 'author','manage_categories' );
  }
}

/**
 * Rename "author/" slug to "student/"
 */
add_action('init', 'cng_author_base');
function cng_author_base() {
    global $wp_rewrite;
    $author_slug = 'student'; // change slug name
    $wp_rewrite->author_base = $author_slug;
}