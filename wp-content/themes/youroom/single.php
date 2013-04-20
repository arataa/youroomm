<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
<link href="<?php bloginfo('template_url'); ?>/css/post.css" rel="stylesheet" type="text/css" />

    <div class="yourm_wrapper">
      <div id="content" role="main" class="above">

			  <?php if ( have_posts() ) : ?>

					<?php get_template_part( 'content-room', get_post_format() ); ?>

					<?php//comments_template( '', false ); ?>

				<?php endif; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
