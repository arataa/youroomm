
<div class="wrapper">
  <div id="leftsidebar">
    <?php if ( strcmp(get_post_meta($post->ID, '_wp_page_template', true), 'list.php') == 0 ) { ?>
    <div class="left_box1">
      <img src="<?php image_path("list/list_place.jpg") ?>"/>
      <img src="<?php image_path("list/list_map.jpg") ?>"/>
    </div>
    <?php } ?>

  <?php if ( is_user_logged_in() == false ) { ?>
    <div class="left_box2">
      <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <a href="<?php echo get_permalink( get_page_by_title('Management')) ?>"><img class="member"src="<?php image_path("aboutus/member.jpg")?>"/></a>
        <p>ログインID</p>
        <input name="log" type="text" class="login">
        <p>パスワード</p>
        <input name="pwd" type="password" class="login">
        <input name="a" type="hidden" value="login">
        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
        <div class="forgot">
          <p>パスワードを忘れた方</p>
          <a href="#" onclick="submit(); return false;" class="loginbtn"><img src="<?php image_path("aboutus/loginbtn.jpg")?>"/></a>
       </div>
     </form>
   </div>
  <?php }else{ ?>
    <div class="left_box5">
      <?php $local_avatars = get_user_meta( $current_user->ID, 'simple_local_avatar', true ); ?>
      <div class="box5inner"><img src="<?php echo $local_avatars["full"] ?>"style="width:60px;height:60px;"/>
        <p style="padding-top:10px;"><?php the_author_meta('first_name',$current_user->ID)?> <?php the_author_meta('last_name',$current_user->ID) ?></p>
        <p><?php the_author_meta('city',$current_user->ID) ?></p>
        <p><?php the_author_meta('country',$current_user->ID) ?></p>
      </div>
      <a href="<?php echo add_query_arg('action', 'logout'); ?>" class="login"><img src="<?php image_path("aboutus/logoutbtn.jpg")?>"/></a>
    </div>
  <?php } ?>

   <div class="left_box6">
     <ul class="detail">
       <li><a href="<?php echo get_permalink( get_page_by_title('Aboutus')) ?>">会社紹介</a></li>
       <li><a href="<?php echo get_permalink( get_page_by_title('Aboutus')) ?>">注意事項/ポリシー</a></li>
     </ul> 
   </div>
</div>
