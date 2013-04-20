<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

//temprally
ob_start();
?> <!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes();?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta name="keywords" content="$B%9%F%$@h$r4JC1$K8!:w(B,$Be:No$G0B$$=I$rC5$9(B,$BN99T@h$N=I$r4JC18!:w(B">
<meta name="description" content="$B<+J,$N4uK>>r7o$K$"$C$?N99T@h$N$*It20$r4JC1$K8!:w=PMh$k%5%$%H(BYOUROOM!!">
<link rel="stylesheet" type="text/css" href="post.css"/>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();

  $action = $_GET["action"];
  if ( strcmp($action,"logout") == 0 ) {
    wp_logout();
    $url = remove_query_arg("action");
    wp_redirect($url);
    return ;
  }
?>
</head>

<body>
<div class="content">
	<header>
			<?php
        if ( strcmp(get_post_meta($post->ID, '_wp_page_template', true), 'list.php') == 0 ) {
          echo wp_get_attachment_image(28,'original');
          echo '<div class="searchbar">';
        }

				// Check to see if the header image has been removed
				$header_image = get_header_image();
				if ( $header_image ) :
					// Compatibility with versions of WordPress prior to 3.4.
					if ( function_exists( 'get_custom_header' ) ) {
						// We need to figure out what the minimum width should be for our featured image.
						// This result would be the suggested width if the theme were to implement flexible widths.
						$header_image_width = get_theme_support( 'custom-header', 'width' );
					} else {
						$header_image_width = HEADER_IMAGE_WIDTH;
					}
					?>
			<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
					// The header image
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() && has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( $header_image_width, $header_image_width ) ) ) &&
							$image[1] >= $header_image_width ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
					else :
						// Compatibility with versions of WordPress prior to 3.4.
						if ( function_exists( 'get_custom_header' ) ) {
							$header_image_width  = get_custom_header()->width;
							$header_image_height = get_custom_header()->height;
						} else {
							$header_image_width  = HEADER_IMAGE_WIDTH;
							$header_image_height = HEADER_IMAGE_HEIGHT;
						}
						?>
					<img src="<?php header_image(); ?>" width="<?php echo $header_image_width; ?>" height="<?php echo $header_image_height; ?>" alt="" />
				<?php endif; // end check for featured image or standard header ?>
			</a></h1>
      <img src="<?php echo get_bloginfo('template_directory') ?>/images/headers/place_search.jpg" style="padding-top:10px;"/>


			<?php
        if ( strcmp(get_post_meta($post->ID, '_wp_page_template', true), 'list.php') == 0 ) {
          echo '<a class="rentbtn"href="'.get_permalink( get_page_by_title('Management')).'" style="float:right;"><img src="'.get_bloginfo('template_directory').'/images/headers/search_rent.jpg"/></a>';
        }else{
			?>
	        <?php //get_search_form(); ?>
          <nav class="headnav">
            <ul>
              <li><a href="<?php echo get_permalink( get_page_by_title('Aboutus')) ?>">会社紹介</li>
              <li><a href="#">ログイン</li>
              <li><a href="<?php echo get_permalink( get_page_by_title('Aboutus')) ?>">注意事項／ポリシー</a></li>
            </ul>
          <?php }; endif; // end check for removed header image ?>

			<?php
        if ( strcmp(get_post_meta($post->ID, '_wp_page_template', true), 'list.php') == 0 ) {
          echo '</div>';
        }
			?>

			<?php
				// Has the text been hidden?
				if ( 'blank' == get_header_textcolor() ) :
			?>
			<?php
				else :
			?>
			<?php endif; ?>
			</nav><!-- #headnav -->
	</header><!-- #branding -->
