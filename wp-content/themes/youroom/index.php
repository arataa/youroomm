<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>
<?php
  //include_once('wp-content/plugins/wp-members/wp-members.php');
  include_once('wp-content/plugins/wp-members/wp-members-dialogs.php');

  if ( is_user_logged_in() == false ) {
    echo wpmem_inc_login();
    echo '<br/>';
    echo '<a href="'.get_permalink( get_page_by_title('Management')).'"> new user </a>';
  }else{
    echo $current_user->lastname;
  }

?>
<?php get_footer(); ?>
