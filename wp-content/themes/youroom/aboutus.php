<?php
/*
 * Template Name: AboutUs Template
 */
get_header(); ?>

<link href="<?php bloginfo('template_url'); ?>/css/aboutus.css" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_url'); ?>/css/signup.css" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_url'); ?>/css/responsive.css" rel="stylesheet" type="text/css" />

<?php include_once 'leftbar.php'; ?>
<?php while ( have_posts() ) : the_post(); ?>
  <?php the_content(); ?>
<?php endwhile ?>
<?php get_footer(); ?>
