<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'youroom_setup' );

if ( ! function_exists( 'youroom_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom headers
 * 	and backgrounds, and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function youroom_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyeleven', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// Grab Twenty Eleven's Ephemera widget.
	require( get_template_directory() . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	$theme_options = twentyeleven_get_theme_options();
	if ( 'dark' == $theme_options['color_scheme'] )
		$default_background_color = '1d1d1d';
	else
		$default_background_color = 'e2e2e2';

	// Add support for custom backgrounds.
	add_theme_support( 'custom-background', array(
		// Let WordPress know what our default background color is.
		// This is dependent on our current color scheme.
		'default-color' => $default_background_color,
	) );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// Add support for custom headers.
	$custom_header_support = array(
		// The default header text color.
		'default-text-color' => '000',
		// The height and width of our custom header.
		'width' => apply_filters( 'twentyeleven_header_image_width', 1000 ),
		'height' => apply_filters( 'twentyeleven_header_image_height', 288 ),
		// Support flexible heights.
		'flex-height' => true,
		// Random image rotation by default.
		'random-default' => true,
		// Callback for styling the header.
		'wp-head-callback' => 'twentyeleven_header_style',
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => 'twentyeleven_admin_header_style',
		// Callback used to display the header preview in the admin.
		'admin-preview-callback' => 'twentyeleven_admin_header_image',
	);

	add_theme_support( 'custom-header', $custom_header_support );

	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR', $custom_header_support['default-text-color'] );
		define( 'HEADER_IMAGE', '' );
		define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
		add_custom_image_header( $custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback'] );
		add_custom_background();
	}

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

	// Add Twenty Eleven's custom image sizes.
	// Used for large feature (header) images.
	add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );
	// Used for featured posts if a large-feature doesn't exist.
	add_image_size( 'small-feature', 500, 300 );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Wheel', 'twentyeleven' )
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Shore', 'twentyeleven' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Trolley', 'twentyeleven' )
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Pine Cone', 'twentyeleven' )
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Chessboard', 'twentyeleven' )
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Lanterns', 'twentyeleven' )
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Willow', 'twentyeleven' )
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Hanoi Plant', 'twentyeleven' )
		)
	) );
}
endif; // twentyeleven_setup

if ( ! function_exists( 'twentyeleven_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_header_style() {
	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( $text_color == HEADER_TEXTCOLOR )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo $text_color; ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyeleven_header_style

if ( ! function_exists( 'twentyeleven_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // twentyeleven_admin_header_style

if ( ! function_exists( 'twentyeleven_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_image() { ?>
	<div id="headimg">
		<?php
		$color = get_header_textcolor();
		$image = get_header_image();
		if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

if ( ! function_exists( 'twentyeleven_continue_reading_link' ) ) :
/**
 * Returns a "Continue Reading" link for excerpts
 */
function twentyeleven_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) . '</a>';
}
endif; // twentyeleven_continue_reading_link

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyeleven_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function twentyeleven_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentyeleven_widgets_init' );

if ( ! function_exists( 'twentyeleven_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function twentyeleven_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr( $html_id ); ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}
endif; // twentyeleven_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'twentyeleven' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );

?>
<?php
function image_path($path) {
  $content  = get_bloginfo('template_directory') . '/images/' . $template . $path;
  echo $content;
}  

function get_image_path($path) {
  return get_bloginfo('template_directory') . '/images/' . $template . $path;
}  

function my_function_admin_bar($content) {
  return ( current_user_can("administrator") ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'my_function_admin_bar');

/**
 * Return select array from array continents
 *   return example "Asia|0, North America|1"
 *  @param  mix   $continet   mix array of continents
 *  @return array $result     array for makking select format1
 */
function get_continents($continents){
  $continents = array(1 => 'アジア', 2 => 'ヨーロッパ', 3 => '北アメリカ', 4 => '南アメリカ',
                                        5 => 'アフリカ', 6 => 'ユーラシア');
  $result = array();
  foreach ( $continents  as $num => $continent ) {
    array_push($result,$continent.'|'.$num);
  }
  return $result;
}

/**
 * Update user infromation from posts
 *  @return none
 */
function update_user($current_user){
  update_user_meta( $current_user->ID, 'last_name' , $_POST['last_name'] );
  update_user_meta( $current_user->ID, 'first_name', $_POST['first_name'] );
  update_user_meta( $current_user->ID, 'sex'       , $_POST['sex'] );
  update_user_meta( $current_user->ID, 'birthday1' , $_POST['birthday1'] );
  update_user_meta( $current_user->ID, 'birthday2' , $_POST['birthday2'] );
  update_user_meta( $current_user->ID, 'birthday3' , $_POST['birthday3'] );
  update_user_meta( $current_user->ID, 'email'     , $_POST['user_email'] );
  if ( strcmp( $_POST['password'], $current_user->user_pass ) != 0 ) {
    wp_update_user( array ( 'ID' => $current_user->ID, 'user_pass' => $_POST['password'] ) );
  }
  update_user_meta( $current_user->ID, 'phone1', $_POST['phone1'] );
  update_user_meta( $current_user->ID, 'addr1', $_POST['addr1'] );
  update_user_meta( $current_user->ID, 'city', $_POST['city'] );
  update_user_meta( $current_user->ID, 'country', $_POST['country'] );
  update_user_meta( $current_user->ID, 'zip', $_POST['zip'] );
  update_user_meta( $current_user->ID, 'continent', $_POST['continent'] );
  update_user_meta( $current_user->ID, 'stay_years', $_POST['stay_years'] );
  update_user_meta( $current_user->ID, 'visit_countries', $_POST['visit_countries'] );
  update_user_meta( $current_user->ID, 'want_visit_countries', $_POST['want_visit_countries'] );
  update_user_meta( $current_user->ID, 'hobby', $_POST['hobby'] );
  update_user_meta( $current_user->ID, 'comment', $_POST['comment'] );

  if ( !empty( $_POST['image_url'] ) ) {
    update_user_meta( $current_user->ID, 'simple_local_avatar', array( 'full' => $_POST['image_url'] ) ); 
  }
}

function update_post($post, $post_post, $current_user){
  //print_r($post_post);
  if ( empty($post) ) {
    $my_post = array(
      'post_title'    => $post_post['propertytitle'],
      'post_status'   => 'publish',
      'post_author'   => $current_user->ID,
      'category'      => 'room',
    );
    $id   = wp_insert_post( $my_post );
    $post = get_post($id);
  }else{
    $post->post_title = $post_post['propertytitle'];
    wp_update_post( $post );
  }
  update_post_meta($post->ID, "price", $post_post['price']);
  update_post_meta($post->ID, "price_period_unit", $post_post['price_period_unit']);
  update_post_meta($post->ID, "period", $post_post['period']);
  update_post_meta($post->ID, "checkin_date", $post_post['from']);
  update_post_meta($post->ID, "checkout_date", $post_post['to']);
  update_post_meta($post->ID, "address", $post_post['address']);
  update_post_meta($post->ID, "comment", $post_post['comment']);
  //update_post_meta($post->ID, "image1", $post_post['image_urls'][1]);
  //print_r($post_post);

  foreach ( $post_post['image_urls'] as $key=>$url ) {
    $image_id = get_field('image'.$key,$post->ID);
    if ( !empty($image_id) ) {
      // image already registered
      $image_post             = get_post($image_id);
      $image_post->post_title = basename($url) ;
      wp_update_post( $image_post );
      //$url_short = preg_replace('/^.*uploads\//i','', $url); 
      //update_post_meta($image_post->ID, "_wp_attached_file", $url_short );

      update_attached_file( $image_id, $url );
      $attach_id = $image_id;

    }elseif (!empty($url)){
      // no image
      $wp_filetype = wp_check_filetype(basename($url), null );
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => basename($url),
        'post_content' => '',
        'post_excerpt' => '',
        'post_status' => 'inherit'
      );
      $attach_id   = wp_insert_attachment( $attachment, $url );
      update_post_meta($post->ID, "image".$key, $attach_id);
    }
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $url );
    wp_update_attachment_metadata( $attach_id, $attach_data );

  }
  return true;
}

/*
  Write property profile
  @param mix $post post object
  @param interger $id post id for new post ( less than and equal 0)
  @return html
*/
function property_profile($post, $id = 0){
  $title           = get_the_title($post->ID);
  if ( empty($post) ) $title = "";
  if ( empty($post) ) {
    $post->ID = (int)$id;
    $price             = "";
    $address           = "";
    $checkin_date      = "";
    $checkout_date     = "";
    $comment           = "";
    $period            = "";
    $price_period_unit = "";
  }else{
    $price             = get_field('price',$post->ID);
    $address           = get_field('address',$post->ID);
    $checkin_date      = get_field('checkin_date',$post->ID);
    $checkout_date     = get_field('checkout_date',$post->ID);
    $comment           = get_field('comment',$post->ID);
    $period            = get_field('period',$post->ID);
    $price_period_unit = get_field('price_period_unit',$post->ID);
  }
  $price   = empty( $_POST['posts'][$post->ID]['price'] )? $price : $_POST['posts'][$post->ID]['price'];
  $address = empty( $_POST['posts'][$post->ID]['address'] )? $address : $_POST['posts'][$post->ID]['address'];
  $checkin_date    = empty( $_POST['posts'][$post->ID]['checkin_date'])? $checkin_date : $_POST['posts'][$post->ID]['checkin_date'];
  $checkout_date   = empty( $_POST['posts'][$post->ID]['checkout_date'])? $checkout_date : $_POST['posts'][$post->ID]['checkout_date'];
  $checkin_date    = new DateTime( $checkin_date);
  $checkout_date   = new DateTime( $checkout_date );
  $comment         = empty( $_POST['posts'][$post->ID]['comment'])? $comment : $_POST['posts'][$post->ID]['comment'];

  $period_field                     = get_field_object('period');
  $period_field['class']            = "period";
  $period_field['name']             = "posts[".$post->ID."][period]";
  $price_period_unit_field          = get_field_object('price_period_unit');
  $price_period_unit_field['class'] = "price_period_unit";
  $price_period_unit_field['name']  = "posts[".$post->ID."][price_period_unit]";
  $acf           = new acf_Select("");

  /*
  ob_start();
  $acf->create_field($period);
  $period_form = ob_get_contents();
  ob_end_clean();

  ob_start();
  $acf->create_field($price_period_unit);
  $price_period_unit_form = ob_get_contents();
  ob_end_clean();
  */

  $val   = empty($_POST['posts'][$post->ID]['period'])? $period : $_POST['posts'][$post->ID]['period'] ;
  $periods = array();
  for ( $i = 1 ; $i <= 12 ; $i++ ) { array_push($periods,$i.'|'.$i); };
  $period_form = wpmem_create_formfield($period_field['name'],'select',$periods,$val,$period_field['class']);

  $val   = empty($_POST['posts'][$post->ID]['price_period_unit'])? $price_period_unit : $_POST['posts'][$post->ID]['price_period_unit'] ;
  $period_units = array();
  array_push($period_units,'/月 | 1');
  array_push($period_units,'/週 | 2');
  array_push($period_units,'/日 | 3'); 
  $price_period_unit_form = wpmem_create_formfield($price_period_unit_field['name'],'select',$period_units,$val,$price_period_unit_field['class']);

  $res = "";
  //wp_get_attachment_image( $image1[id], "medium");
$res = '
<div class="lendinfo2">
  <div  class="subtitle">
    <a class="text1">物件プロフィール</a>
    <a class="deletebtn" href="#" onclick="return false;" p_id="'.$post->ID.'"><img src="'.get_image_path("management/deletebtn.jpg").'"/></a>
  </div>
  <div class="border"></div>
  <div class=" property1">
    <table class="property1innner" height="300"width="480" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="59"><span style="color:red;">*</span>タイトル</td>
      <td colspan="3"><input name="posts['.$post->ID.'][propertytitle]" value="'.$title.'" type="text" style="border:1px black solid;float:left; border-radius:3px;width:380px;height:22px;"></td>
    </tr>
    <tr>
      <td><span style="color:red;">*</span>料金</td>
      <td><input name="posts['.$post->ID.'][price]" type="text" value="'.$price.'" style="border:1px black solid;float:left; border-radius:3px;width:80px;height:22px;"></td>
      <td colspan="2">'.$price_period_unit_form.'</td>
    </tr>
    <tr>
      <td><span style="color:red;">*</span>期間</td>
      <td align="left" valign="middle">'.$period_form.'
      &nbsp;月</td>
      <td><input name="posts['.$post->ID.'][from]" type="text" value="'.$checkin_date->format('m/d/Y').'" id="datepicker" style="border:1px black solid;float:left; border-radius:3px;width:100px;height:22px;">&nbsp;IN</td>
      <td><input name="posts['.$post->ID.'][to]" type="text" value="'.$checkout_date->format('m/d/Y').'" id="datepicker2" style="border:1px black solid;float:left; border-radius:3px;width:100px;height:22px;">&nbsp;OUT</td>
    </tr>
    <tr>
      <td><span style="color:red;">*</span>住所</td>
      <td colspan="3"><input name="posts['.$post->ID.'][address]" value="'. $address .'" type="text" style="border:1px black solid;float:left; border-radius:3px;width:360px;height:22px;"></td>
    </tr>
    <tr>
      <td><span style="color:red;">*</span>コメント</td>
      <td colspan="3"><textarea name="posts['.$post->ID.'][comment]" type="text" style="border:1px black solid;float:left; border-radius:3px;width:380px;height:144px;">'.$comment.'</textarea></td>
    </tr>
    </table>
  </div>
  <div class="propertypic">
    <table width="280" border="0" cellspacing="0" cellpadding="0">';

  for ( $i = 1 ; $i <= 10 ; $i++ ) {
    $res = $res . create_input_picture($post,$i) ;
  }

  $res = $res . '
    </table>
  </div>
</div>
';
  return $res;
}

/*
  @param object  $post
  @param int     $i
  @return string $res
*/
function create_input_picture($post, $i){
  if ( empty($post->ID) ) {
    $url   = "";
    $title = "";
  }else{
    $image_id = get_field('image'.$i,$post->ID);
    $url      = wp_get_attachment_url( $image_id );
    $title    = basename($url);
  }
  if ( !empty( $_POST['posts'][$post->ID]['image_urls'][$i]) ) {
    $url   = $_POST['posts'][$post->ID]['image_urls'][$i];
    $title = basename( $url ) ;
  }

  $res = '
      <tr>
        <td align="left" ><span style="color:red;">*</span>写真'.$i.'</td>
        <td><img class="image'.$i.'" src="'.get_image_path("management/selectbtn.jpg").'" alt="写真を選ぶ"/>
        <input type="file" name="upload" class="propety_pic"  style="display:none;" />
        <input type="text" value="'.$url.'" name="posts['.$post->ID.'][image_urls]['.$i.']" style="display:none;" /></td>
        <td class="file_name" >'.$title.'</td>
      </tr>
  ';
  return $res;
}

?>
