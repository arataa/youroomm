<?php
/*
 * Template Name: List Template
 */
get_header(); ?>

<?php
  //include_once('wp-content/plugins/wp-members/wp-members-dialogs.php');
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  query_posts( array('author' => $current_user->ID, 'orderby' => 'id', 'order' => 'ASC', 
                     'posts_per_page' => 2, 'paged' => $paged) );
?>


<link href="<?php bloginfo('template_url'); ?>/css/list.css" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_url'); ?>/css/signup.css" rel="stylesheet" type="text/css" />

<?php include_once 'leftbar.php'; ?>

<div id="listcontents">
<img src="<?php image_path("list/youroom_listbar.jpg") ?>"/></a></li>

 <?php if ( have_posts() ) : ?>
   <?php while ( have_posts() ) : the_post(); ?>
    <?php 
      $image1_id  = get_field('image1',$post->ID);
      $price      = get_field('price',$post->ID);
      $address    = get_field('address',$post->ID);
      
      echo wp_get_attachment_image( $image1_id, "medium",'',array('class' => 'list') );
    ?>
    <div class="listinner">
      <ul class="list1">
        <li>
          <a href="<?php echo get_permalink( $post->ID ); ?>"><img src="<?php image_path("list/list_pic.jpg") ?>"/></a>
        </li>
        <li>
          <a href="<?php echo get_permalink( $post->ID ); ?>"><?php the_title() ?></a>
        </li>
        <li>
          <a href="<?php echo get_permalink( $post->ID ); ?>">$<?php echo $price ?></a>
        </li>
      </ul>
      <ul class="list2">
        <li style="padding-top:10px;" >
          <a href="<?php echo get_permalink( $post->ID ); ?>" ><img src="<?php image_path("list/list-address.jpg")?>" /></a>
       </li>
       <li style="padding-top:10px;" >
         <a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $address ?></a>
       </li>
       <li style="padding-top:10px;" >
         <a href="<?php echo get_permalink( $post->ID ); ?>" ><img src="<?php image_path("list/askdetail.jpg")?>" /></a>
       </li>
       <li style="padding-top:10px;" >
        <a href="#" ><img src="<?php image_path("list/contact.jpg")?>" /></a>
       </li>
    </ul>
  </div>
   <?php endwhile ?>
<?php endif ?>
<?php wp_pagenavi(); ?>

</div>

</div>

<?php get_footer(); ?>
